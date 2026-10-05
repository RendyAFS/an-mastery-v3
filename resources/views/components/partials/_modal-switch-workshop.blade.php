@php
    $hasSelectedWorkshop = !is_null($user?->workshop_id);
@endphp

<x-modal id="modal-switch-workshop" title="{{ __('workshop.switch_workshop') }}" size="3xl" :scrollable="true" :centered="true" :closeable="$hasSelectedWorkshop">
    <form id="form-switch-workshop" method="POST" action="{{ route('workshops.switch') }}">
        @csrf
        <div class="space-y-4">
            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray)">
                {{ __('workshop.choose_workshop_desc') }}
            </p>

            <div id="switch-workshop-list" class="grid grid-cols-1 md:grid-cols-2 gap-3.5 max-h-[60vh] overflow-y-auto custom-scrollbar p-1">
                {{-- Loading Skeleton placeholder --}}
                <div class="col-span-full py-8 flex flex-col items-center justify-center gap-2 text-(--color-dark-gray) dark:text-(--color-gray)">
                    <div class="size-6 border-2 border-(--color-primary) border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs">{{ __('button-loading.Loading...') ?? 'Memuat...' }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            @if ($hasSelectedWorkshop)
                <button type="button" data-hs-overlay="#modal-switch-workshop"
                    class="py-2 px-4 rounded-lg border border-(--color-gray)/40 hover:bg-(--color-gray)/20 text-sm font-medium text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                    {{ __('workshop.cancel') }}
                </button>
            @endif

            <button type="submit" id="btn-submit-switch-workshop"
                class="py-2 px-4 rounded-lg bg-(--color-primary) hover:bg-(--color-primary)/90 text-sm font-medium text-white shadow-xs cursor-pointer flex items-center gap-2">
                <i data-lucide="check" class="size-4"></i>
                <span>{{ __('workshop.select_workshop') }}</span>
            </button>
        </div>
    </form>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalEl = document.getElementById('modal-switch-workshop');
        const form = document.getElementById('form-switch-workshop');
        const listContainer = document.getElementById('switch-workshop-list');
        const activeListUrl = '{{ route("workshops.active-list") }}';
        const currentLangCurrent = '{{ __("workshop.current_workshop") }}';
        const noDataText = '{{ __("crud.no_data_available") ?? "Belum ada workshop aktif" }}';
        const mustSelectWorkshop = @json(!$hasSelectedWorkshop);
        let isFetching = false;
        let isSubmitting = false;

        const renderWorkshops = (workshops, currentWorkshopId) => {
            if (!listContainer) return;

            if (!workshops || workshops.length === 0) {
                listContainer.innerHTML = `
                    <div class="col-span-full text-center py-8 text-sm text-(--color-dark-gray) dark:text-(--color-gray)">
                        ${noDataText}
                    </div>
                `;
                return;
            }

            let html = '';
            workshops.forEach((w) => {
                const isSelected = (currentWorkshopId && Number(currentWorkshopId) === Number(w.id)) || w.is_current;
                const borderClass = isSelected
                    ? 'border-(--color-primary) bg-(--color-primary)/5 dark:bg-(--color-primary)/10'
                    : 'border-(--color-gray)/20 hover:border-(--color-gray)/60 bg-(--color-light) dark:bg-(--color-dark-slate)/40';
                
                const imageHtml = w.image_url
                    ? `<img src="${w.image_url}" alt="${w.name}" class="size-11 rounded-xl object-cover shrink-0 border border-(--color-gray)/20 mt-0.5">`
                    : `<div class="size-11 rounded-xl bg-(--color-primary)/10 text-(--color-primary) flex items-center justify-center shrink-0 font-bold text-sm mt-0.5">${w.initials}</div>`;

                const badgeHtml = isSelected
                    ? `<span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-(--color-primary)/15 text-(--color-primary) shrink-0">${currentLangCurrent}</span>`
                    : '';

                const locationHtml = w.location
                    ? `<p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-1 line-clamp-2 leading-relaxed">${w.location}</p>`
                    : '';

                html += `
                    <label class="relative flex items-start gap-3.5 p-3.5 rounded-xl border-2 transition-all cursor-pointer select-none ${borderClass}">
                        <input type="radio" name="workshop_id" value="${w.id}" class="hidden peer" ${isSelected ? 'checked' : ''} required>
                        ${imageHtml}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-sm font-bold text-(--color-dark) dark:text-(--color-light) leading-snug">
                                    ${w.name}
                                </span>
                                ${badgeHtml}
                            </div>
                            ${locationHtml}
                        </div>
                        <div class="size-5 rounded-full border-2 border-(--color-gray)/40 peer-checked:border-(--color-primary) peer-checked:bg-(--color-primary) flex items-center justify-center shrink-0 transition-colors mt-0.5">
                            <i data-lucide="check" class="size-3 text-white hidden peer-checked:block"></i>
                        </div>
                    </label>
                `;
            });

            listContainer.innerHTML = html;

            const radioLabels = listContainer.querySelectorAll('label');
            listContainer.querySelectorAll('input[name="workshop_id"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    radioLabels.forEach((label) => {
                        const r = label.querySelector('input[name="workshop_id"]');
                        if (r && r.checked) {
                            label.classList.remove('border-(--color-gray)/20', 'bg-(--color-light)', 'dark:bg-(--color-dark-slate)/40');
                            label.classList.add('border-(--color-primary)', 'bg-(--color-primary)/5', 'dark:bg-(--color-primary)/10');
                        } else if (r) {
                            label.classList.remove('border-(--color-primary)', 'bg-(--color-primary)/5', 'dark:bg-(--color-primary)/10');
                            label.classList.add('border-(--color-gray)/20', 'bg-(--color-light)', 'dark:bg-(--color-dark-slate)/40');
                        }
                    });
                });
            });

            if (window.lucide) {
                window.lucide.createIcons();
            }
        };

        const loadActiveWorkshops = async () => {
            if (isFetching) return;
            isFetching = true;

            try {
                const res = await fetch(activeListUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    renderWorkshops(data.workshops, data.current_workshop_id);
                }
            } catch (err) {
                console.error('Failed to load active workshops:', err);
            } finally {
                isFetching = false;
            }
        };

        loadActiveWorkshops();

        if (modalEl) {
            modalEl.addEventListener('open.hs.overlay', () => {
                loadActiveWorkshops();
            });

            if (mustSelectWorkshop) {
                modalEl.addEventListener('close.hs.overlay', () => {
                    if (!isSubmitting) {
                        setTimeout(() => {
                            if (window.HSOverlay) {
                                HSOverlay.open('#modal-switch-workshop');
                            }
                        }, 50);
                    }
                });
            }
        }

        document.querySelectorAll('[data-hs-overlay="#modal-switch-workshop"]').forEach((btn) => {
            btn.addEventListener('click', () => {
                loadActiveWorkshops();
            });
        });

        if (mustSelectWorkshop) {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            const openMandatoryModal = () => {
                if (window.HSOverlay) {
                    HSOverlay.open('#modal-switch-workshop');
                } else {
                    setTimeout(openMandatoryModal, 100);
                }
            };
            setTimeout(openMandatoryModal, 150);
        }

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('btn-submit-switch-workshop');
                const selectedRadio = form.querySelector('input[name="workshop_id"]:checked');
                
                if (!selectedRadio) {
                    if (window.Toast) {
                        window.Toast.warning(null, '{{ __("workshop.must_select_workshop") }}');
                    }
                    return;
                }

                isSubmitting = true;
                const originalHtml = btn ? btn.innerHTML : '';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="inline-block size-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>';
                }

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        if (window.Toast) {
                            window.Toast.success(null, data.message || 'Workshop switched successfully');
                        }
                        if (window.HSOverlay) {
                            HSOverlay.close('#modal-switch-workshop');
                        }
                        setTimeout(() => {
                            window.location.reload();
                        }, 400);
                    } else {
                        isSubmitting = false;
                        if (window.Toast) {
                            window.Toast.error(null, data.message || 'Failed to switch workshop');
                        }
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                        }
                    }
                } catch (err) {
                    isSubmitting = false;
                    console.error(err);
                    if (window.Toast) {
                        window.Toast.error(null, 'An unexpected error occurred');
                    }
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                }
            });
        }
    });
</script>

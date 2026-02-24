@extends('layouts.main', ['title' => 'Edit Image Fabric'])

@push('scripts')
    @vite('resources/js/pages/image-fabric/form.js')
@endpush

@section('content')
    <form id="image-fabric-form" data-mode="edit" data-id="{{ $imageFabric->id }}" class="max-w-4xl mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray)
               shadow-2xs rounded-xl
               dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div class="p-4 md:p-5">
                <p class="text-2xl font-bold text-(--color-dark) dark:text-(--color-light)">
                    Edit Image Fabric
                </p>

                <div class="mt-6">
                    @include('image-fabric.form', ['image-fabric' => $imageFabric])
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="bg-(--color-light) shadow-md
                   rounded-b-xl py-3 px-4 md:px-5 flex gap-2
                   dark:bg-(--color-dark) dark:border-(--color-slate)">

                <x-button-loading type="submit" text="Update" loadingText="Updating..."
                    color="bg-(--color-success) hover:bg-(--color-success)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('image-fabrics.index') }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg
                       bg-(--color-danger) hover:bg-(--color-danger)/70 text-(--color-light) cursor-pointer
                       hover:opacity-90 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
@endsection

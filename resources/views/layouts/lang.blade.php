@php
    $langCrud = collect(trans('crud'))->toArray();
    $langModels = collect(trans('models'))->toArray();
    $langUi = collect(trans('ui'))->toArray();
    $langFilepond = collect(trans('filepond'))->toArray();
    $langEnums = collect(trans('enums'))->toArray();
    $langCustomAlert = [
        'success' => __('custom-alert.Success'),
        'info' => __('custom-alert.Information'),
        'warning' => __('custom-alert.Warning'),
        'error' => __('custom-alert.Error'),
        'ok' => __('custom-alert.OK'),
        'confirm' => __('custom-alert.Confirm'),
        'cancel' => __('custom-alert.Cancel'),
        'confirmation' => __('custom-alert.Confirmation'),
        'deleteConfirmation' => __('custom-alert.Delete Confirmation'),
        'delete' => __('custom-alert.Delete'),
        'deleteMessage' => __('custom-alert.Are you sure you want to delete this item? This action cannot be undone.'),
    ];
    $langApiProvider = [
        'networkError' => __('api-provider.Network Error or Server Down'),
        'invalidSession' => __('api-provider.Invalid Session'),
        'accessDenied' => __('api-provider.Access Denied'),
        'notFound' => __('api-provider.Data not found'),
        'pageExpired' => __('api-provider.Page Expired. Refreshing...'),
        'validationError' => __('api-provider.Validation Error'),
        'serverError' => __('api-provider.Server Error'),
        'internalServerError' => __('api-provider.Internal Server Error'),
        'unknownError' => __('api-provider.Unknown Error'),
    ];
    // CRUD
    $langDashboard = collect(trans('dashboard'))->toArray();
    $langUser = collect(trans('user'))->toArray();
    $langEmployee = collect(trans('employee'))->toArray();
    $langRole = collect(trans('role'))->toArray();
    $langSupplier = collect(trans('supplier'))->toArray();
    $langGallery = collect(trans('gallery'))->toArray();
    $langImageFabric = collect(trans('image-fabric'))->toArray();
    $langColorFabric = collect(trans('color-fabric'))->toArray();
    $langTypeFabric = collect(trans('type-fabric'))->toArray();
    $langTypeColor = collect(trans('type-color'))->toArray();
    $langPriceSupplier = collect(trans('price-supplier'))->toArray();
    $langPriceEmployee = collect(trans('price-employee'))->toArray();
    $langPresence = collect(trans('presence'))->toArray();
    $langFabric = collect(trans('fabric'))->toArray();
    $langSablon = collect(trans('sablon'))->toArray();
    $langBillSupplier = collect(trans('bill-supplier'))->toArray();
    $langSalaryEmployee = collect(trans('salary-employee'))->toArray();
    $langMemo = collect(trans('memo'))->toArray();
    $langMyProfile = collect(trans('my-profile'))->toArray();
    // END CRUD
@endphp

<script>
    window.langCustomAlert = @json($langCustomAlert);
    window.langApiProvider = @json($langApiProvider);
    window.langCrud = @json($langCrud);
    window.langModels = @json($langModels);
    window.langUi = @json($langUi);
    window.langFilepond = @json($langFilepond);
    window.langEnums = @json($langEnums);
    // CRUD
    window.langDashboard = @json($langDashboard);
    window.langUser = @json($langUser);
    window.langEmployee = @json($langEmployee);
    window.langRole = @json($langRole);
    window.langSupplier = @json($langSupplier);
    window.langGallery = @json($langGallery);
    window.langImageFabric = @json($langImageFabric);
    window.langColorFabric = @json($langColorFabric);
    window.langTypeFabric = @json($langTypeFabric);
    window.langTypeColor = @json($langTypeColor);
    window.langPriceSupplier = @json($langPriceSupplier);
    window.langPriceEmployee = @json($langPriceEmployee);
    window.langPresence = @json($langPresence);
    window.langFabric = @json($langFabric);
    window.langSablon = @json($langSablon);
    window.langBillSupplier = @json($langBillSupplier);
    window.langSalaryEmployee = @json($langSalaryEmployee);
    window.langMemo = @json($langMemo);
    window.langMyProfile = @json($langMyProfile);
    // END CRUD
</script>

@php
    $langCrud = collect(trans('crud'))->toArray();
    $langModels = collect(trans('models'))->toArray();
    $langUi = collect(trans('ui'))->toArray();
    $langFilepond = collect(trans('filepond'))->toArray();
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
    $langUser = collect(trans('user'))->toArray();
    $langEmployee = collect(trans('employee'))->toArray();
    $langRole = collect(trans('role'))->toArray();
    $langSupplier = collect(trans('supplier'))->toArray();
    $langImageFabric = collect(trans('image-fabric'))->toArray();
    // END CRUD
@endphp

<script>
    window.langCustomAlert = @json($langCustomAlert);
    window.langApiProvider = @json($langApiProvider);
    window.langCrud = @json($langCrud);
    window.langModels = @json($langModels);
    window.langUi = @json($langUi);
    window.langFilepond = @json($langFilepond);
    // CRUD
    window.langUser = @json($langUser);
    window.langEmployee = @json($langEmployee);
    window.langRole = @json($langRole);
    window.langSupplier = @json($langSupplier);
    window.langImageFabric = @json($langImageFabric);
    // END CRUD
</script>

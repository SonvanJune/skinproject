<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/bootstraps/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/regular.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-management.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skeleton-loading.css') }}">
    <script src="{{ asset('js/axios/axios.min.js') }}"></script>
    <title>Document</title>
</head>

<body>
    <!-- Button trigger modal -->
    <button type="button" class="mt-5 mx-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="display: block;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="header-item">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">File</h1>
                        <ul>
                            <li id="upload-button"><i class="fa-solid fa-arrow-up-from-bracket"></i>Upload
                                <input type="file" name="file" id="file" hidden>
                            </li>
                            <li id="create-folder"><i class="fa-solid fa-folder-plus"></i>Create folder</li>
                        </ul>

                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <button type="button" class="btn btn-primary btn-accept-file">Choose</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Show the folders --}}
                        <div class="col-3 border-right" style="padding-right: 0px">
                            <div class="file-tree scrollable">
                                <ul id="folder-list">
                                </ul>
                            </div>
                        </div>
                        {{-- Show all images in the folder --}}
                        <div class="col-9 files-box" style="padding-right: 0px">
                            <div class="row image-container scrollable">
                            </div>
                            {{-- popup confirm  --}}
                            <div id="confirm-modal"></div> <!-- Container for the confirmation modal -->
                            <div id="input-modal"></div> <!-- Container for the input modal -->
                        </div>
                        {{-- End show all images in the folder --}}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/bootstraps/bootstrap.bundle.min.js') }}"></script>
    <script>
        const defaultImagePath = "{{ asset('images/image-default.png') }}";
    </script>
    <script src="{{asset('js/file-manager/file-manager.js')}}"></script>
    
</body>

</html>

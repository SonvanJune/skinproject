<div class="">
    <div id="toolbar-container"
        style="border: .0625rem solid rgba(231, 234, 243, .7); border-top-left-radius: .3125rem; border-top-right-radius: .3125rem;">
        <span class="ql-formats">
            <select class="ql-font"></select>
            <select class="ql-size"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-bold"></button>
            <button class="ql-italic"></button>
            <button class="ql-underline"></button>
            <button class="ql-strike"></button>
        </span>
        <span class="ql-formats">
            <select class="ql-color"></select>
            <select class="ql-background"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-script" value="sub"></button>
            <button class="ql-script" value="super"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-header" value="1"></button>
            <button class="ql-header" value="2"></button>
            <button class="ql-blockquote"></button>
            <button class="ql-code-block"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-list" value="ordered"></button>
            <button class="ql-list" value="bullet"></button>
            <button class="ql-indent" value="-1"></button>
            <button class="ql-indent" value="+1"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-direction" value="rtl"></button>
            <select class="ql-align"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-link"></button>
            <button class="ql-image" id="openFileModal">
            </button>
        </span>
    </div>
    <div id="editor"
        style="border: .0625rem solid rgba(231, 234, 243, .7); border-top: 0; border-bottom-left-radius: .3125rem; border-bottom-right-radius: .3125rem">
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="imageEditorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header edtor-modal-header">
                <div class="header-left">
                    <div class="btn-back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-arrow-left"></i></div>
                    <h1 class="modal-title" id="exampleModalLabel"></h1>
                </div>
                <div class="header-right">
                    <div id="btn-reset-filter" class="btn-reset">Reset</div>
                    <div id="btn-apply-filter" class="btn-apply" data-bs-dismiss="modal" aria-label="Close"><span><i
                                class="fa-solid fa-check"></i></span>Apply</div>
                </div>
            </div>
            <div class="modal-body">
                <div class="image-editor-container">
                    <div class="row" style="height: 100%">
                        <div class="col-8">
                            <div class="preview-image">
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="col-4 border-left">
                            <div class="img-editor-menu">
                                <div class="img-editor-menu-container">
                                    <div class="img-editor-menu-container__title">
                                        <div class="tool-title">Crop</div>
                                    </div>
                                    <div class="img-editor-menu-section">
                                        <div class="tool-title-sub">Aspect ratio</div>
                                        <div class="img-editor-crop-card-list">
                                            <div id="ratio-free" class="img-editor-crop-card">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor-crop-card__icon">
                                                    <path
                                                        d="M17 15h2V7c0-1.1-.9-2-2-2H9v2h8v8zM7 17V1H5v4H1v2h4v10c0 1.1.9 2 2 2h10v4h2v-4h4v-2H7z">
                                                    </path>
                                                </svg>
                                                <div class="img-editor-crop-card__lable">
                                                    Free
                                                </div>
                                            </div>
                                            <div id="ratio-1-1" class="img-editor-crop-card">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor-crop-card__icon">
                                                    <path
                                                        d="M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H6V6h12v12z">
                                                    </path>
                                                </svg>
                                                <div class="img-editor-crop-card__lable">
                                                    1:1
                                                </div>
                                            </div>
                                            <div id="ratio-4-3" class="img-editor-crop-card">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor-crop-card__icon">
                                                    <path
                                                        d="M19 5H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12H5V7h14v10z">
                                                    </path>
                                                </svg>
                                                <div class="img-editor-crop-card__lable">
                                                    4:3
                                                </div>
                                            </div>
                                            <div id="ratio-3-4" class="img-editor-crop-card">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor-crop-card__icon">
                                                    <path
                                                        d="M17 3H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H7V5h10v14z">
                                                    </path>
                                                </svg>
                                                <div class="img-editor-crop-card__lable">
                                                    3:4
                                                </div>
                                            </div>
                                            <div id="ratio-16-9" class="img-editor-crop-card">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor-crop-card__icon">
                                                    <path
                                                        d="M19 7H5c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 8H5V9h14v6z">
                                                    </path>
                                                </svg>
                                                <div class="img-editor-crop-card__lable">
                                                    16:9
                                                </div>
                                            </div>
                                            <div id="ratio-9-16" class="img-editor-crop-card">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor-crop-card__icon">
                                                    <path
                                                        d="M19 7H5c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 8H5V9h14v6z"
                                                        style="transform: rotate(90deg); transform-origin: center center;">
                                                    </path>
                                                </svg>
                                                <div class="img-editor-crop-card__lable">
                                                    9:16
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="img-editor-menu-section">
                                        <div class="tool-title-sub">Dimensions</div>

                                    </div>
                                </div>
                                <div class="img-editor-menu-container">
                                    <div class="tool-title">Rotate and flip</div>
                                    <div class="img-editor-btns-group">
                                        <div class="img-editor-btns">
                                            <button id="rotate-left" class="btn-ouline btn-option"><svg
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor__icon">
                                                    <path
                                                        d="M7.11 8.53 5.7 7.11C4.8 8.27 4.24 9.61 4.07 11h2.02c.14-.87.49-1.72 1.02-2.47zM6.09 13H4.07c.17 1.39.72 2.73 1.62 3.89l1.41-1.42c-.52-.75-.87-1.59-1.01-2.47zm1.01 5.32c1.16.9 2.51 1.44 3.9 1.61V17.9c-.87-.15-1.71-.49-2.46-1.03L7.1 18.32zM13 4.07V1L8.45 5.55 13 10V6.09c2.84.48 5 2.94 5 5.91s-2.16 5.43-5 5.91v2.02c3.95-.49 7-3.85 7-7.93s-3.05-7.44-7-7.93z">
                                                    </path>
                                                </svg></button>
                                            <button id="rotate-right" class="btn-ouline btn-option"><svg
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor__icon">
                                                    <path
                                                        d="M15.55 5.55 11 1v3.07C7.06 4.56 4 7.92 4 12s3.05 7.44 7 7.93v-2.02c-2.84-.48-5-2.94-5-5.91s2.16-5.43 5-5.91V10l4.55-4.45zM19.93 11c-.17-1.39-.72-2.73-1.62-3.89l-1.42 1.42c.54.75.88 1.6 1.02 2.47h2.02zM13 17.9v2.02c1.39-.17 2.74-.71 3.9-1.61l-1.44-1.44c-.75.54-1.59.89-2.46 1.03zm3.89-2.42 1.42 1.41c.9-1.16 1.45-2.5 1.62-3.89h-2.02c-.14.87-.48 1.72-1.02 2.48z">
                                                    </path>
                                                </svg></button>
                                        </div>
                                        <div class="img-editor-btns">
                                            <button id="flip-horizontal" class="btn-ouline btn-option"><svg
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor__icon">
                                                    <path
                                                        d="M15 21h2v-2h-2v2zm4-12h2V7h-2v2zM3 5v14c0 1.1.9 2 2 2h4v-2H5V5h4V3H5c-1.1 0-2 .9-2 2zm16-2v2h2c0-1.1-.9-2-2-2zm-8 20h2V1h-2v22zm8-6h2v-2h-2v2zM15 5h2V3h-2v2zm4 8h2v-2h-2v2zm0 8c1.1 0 2-.9 2-2h-2v2z"
                                                        style="transform: rotate(0deg); transform-origin: center center;">
                                                    </path>
                                                </svg></button></button>
                                            <button id="flip-vertical" class="btn-ouline btn-option"><svg
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    class="img-editor__icon">
                                                    <path
                                                        d="M15 21h2v-2h-2v2zm4-12h2V7h-2v2zM3 5v14c0 1.1.9 2 2 2h4v-2H5V5h4V3H5c-1.1 0-2 .9-2 2zm16-2v2h2c0-1.1-.9-2-2-2zm-8 20h2V1h-2v22zm8-6h2v-2h-2v2zM15 5h2V3h-2v2zm4 8h2v-2h-2v2zm0 8c1.1 0 2-.9 2-2h-2v2z"
                                                        style="transform: rotate(90deg); transform-origin: center center;">
                                                    </path>
                                                </svg></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="img-editor-menu-container">
                                    <div class="tool-title">File info</div>
                                    <div class="properties-table">
                                        <div class="properties-table-row">
                                            <div class="properties-table-row__label">
                                                Upload date
                                            </div>
                                            <div><span>13/09/2024</span></div>
                                        </div>
                                        <div class="properties-table-row">
                                            <div class="properties-table-row__label">
                                                File size
                                            </div>
                                            <div><span>13kB</span></div>
                                        </div>
                                        <div class="properties-table-row">
                                            <div class="properties-table-row__label">
                                                Width
                                            </div>
                                            <div><span>200 px</span></div>
                                        </div>
                                        <div class="properties-table-row">
                                            <div class="properties-table-row__label">
                                                Height
                                            </div>
                                            <div><span>200 px</span></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('openFileModal').addEventListener('click', () => {
        renderModal('imagePickerModal');
    });
</script>

<!-- Initialize Quill editor -->
<script>
    Quill.register('modules/imageResize', QuillResizeModule);

    window.quill = new Quill('#editor', {
        modules: {
            syntax: false,
            toolbar: {
                container: '#toolbar-container',
                handlers: {
                    image: function() {

                    }
                },
            },
            imageResize: {
                displaySize: true
            },
        },
        placeholder: 'Compose an epic...',
        theme: 'snow',
    });

    quill.root.addEventListener('contextmenu', function(e) {
        if (e.target.tagName === 'IMG') {
            // Add a resize handle to the image
            e.preventDefault()
            const menuBox = document.createElement('div');
            menuBox.className = 'menu-box';
            menuBox.style.borderRadius = '5px';
            menuBox.style.position = 'absolute';

            menuBox.innerHTML = `
              <ul>
                <li id="menu-edit-image" class="menu-item" data-bs-toggle="modal" data-bs-target="#imageEditorModal" style='border-radius: 5px'>
                    <div class="item-icon" style="border-bottom-left-radius: 5px; border-top-left-radius: 5px;">
                      <i class="fa-solid fa-pen"></i>
                    </div>
                    <div class="item-text">
                        Edit
                    </div>
                </li>
            </ul>
            `;

            // Position the menuBox at the mouse click location
            menuBox.style.left = e.pageX + 'px'; // Horizontal position (mouse X)
            menuBox.style.top = e.pageY + 'px'; // Vertical position (mouse Y)

            // Remove any existing menu before adding a new one
            document.querySelectorAll('.menu-box').forEach(menu => menu.remove());
            // Append the menu to the document body
            document.body.appendChild(menuBox);

            document.querySelector('#menu-edit-image').addEventListener('click', function() {
                const previewImage = document.querySelector('.preview-image img');
                previewImage.setAttribute('src', e.target.getAttribute('src'));
                cropperInage(previewImage, e.target);
            })


        }
    });
</script>

<script>
    function cropperInage(image, originalImage) {
        const previewImage = image;
        const rotateOptions = document.querySelectorAll('.btn-option');
        const btnResetFilter = document.getElementById('btn-reset-filter');
        const btnApplyFilter = document.getElementById('btn-apply-filter');
        const aspectRatioes = document.querySelectorAll('.img-editor-crop-card');

        let rotate = 0;
        let flippedX = 1;
        let flippedY = 1;

        const cropper = new Cropper(previewImage, {
            aspectRatio: NaN,
            viewMode: 1,
            preview: '.img-preview',
            movable: true,
            zoomable: true,
            rotatable: true,
            scalable: true,
            autoCrop: false,
        });

        // Handel reset filder
        btnResetFilter.addEventListener('click', function() {
            cropper.reset();
        });

        btnApplyFilter.addEventListener('click', function() {
            // Crop the image according to the selected area using Cropper.js
            const croppedCanvas = cropper.getCroppedCanvas({
                // Set the background fill color to transparent
                fillColor: 'rgba(0, 0, 0, 0)'
            });

            // Convert the cropped canvas into a Blob (binary large object)
            if (croppedCanvas) {
                croppedCanvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    originalImage.src = url;
                }, 'image/jpg');
            } else {
                console.error('Canvas not generated. Ensure there is an image to crop.');
            }
        });


        aspectRatioes.forEach(aspectRatio => {
            aspectRatio.addEventListener('click', function() {
                cropper.crop();
                switch (aspectRatio.id) {
                    case 'ratio-free':
                        cropper.setAspectRatio(NaN);
                        break;
                    case 'ratio-1-1':
                        cropper.setAspectRatio(1);
                        break;
                    case 'ratio-16-9':
                        cropper.setAspectRatio(16 / 9);
                        break;
                    case 'ratio-9-16':
                        cropper.setAspectRatio(9 / 16);
                        break;
                    case 'ratio-4-3':
                        cropper.setAspectRatio(4 / 3);
                        break;
                    case 'ratio-3-4':
                        cropper.setAspectRatio(3 / 4);
                        break;
                    default:
                        console.log('Invalid ratio id');
                }

                const activeCard = 'img-editor-crop-card--active'
                if (aspectRatio.classList.contains(activeCard)) {
                    aspectRatio.classList.remove(activeCard);
                    cropper.clear();
                } else {
                    aspectRatioes.forEach(ar => ar.classList.remove(activeCard));
                    aspectRatio.classList.add(activeCard);
                }


            });

        });
        rotateOptions.forEach(option => {
            // Adding click event listener to all rotate/flip buttons
            option.addEventListener('click', function(event) {
                event.preventDefault()
                event.stopPropagation()
                switch (option.id) {
                    case 'rotate-left':
                        cropper.rotate(-45);
                        break;
                    case 'rotate-right':
                        cropper.rotate(45);
                        break;
                    case 'flip-horizontal':
                        flippedX = flippedX === 1 ? -1 : 1; // Đổi trạng thái lật ngang
                        cropper.scaleX(flippedX); // Áp dụng lật ngang
                        break;
                    case 'flip-vertical':
                        flippedY = flippedY === 1 ? -1 : 1; // Đổi trạng thái lật dọc
                        cropper.scaleY(flippedY); // Áp dụng lật dọc
                        break;
                    default:
                        console.log('Unknown action:', option
                            .id); // Xử lý các trường hợp không xác định
                }
            });
        });

    }
</script>

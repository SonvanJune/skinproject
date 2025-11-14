<!-- Modal -->
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 12px;">
                <div class="header-item">
                    <h1 class="modal-title fs-5" id="fileModalLabel">File</h1>
                    <ul>
                        <li id="upload-button"><i class="fa-solid fa-arrow-up-from-bracket"></i>Upload
                            <input type="file" name="file" id="file" hidden>
                        </li>
                        <li id="create-folder"><i class="fa-solid fa-folder-plus"></i>Create folder</li>
                    </ul>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0">
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <form id="file-upload-form" method="POST" enctype="multipart/form-data">
        <!-- First File Input Group -->
        <div class="file-input-container" data-name="files1" data-max-files="2">
            <div class="file-input">
                <input type="file" name="files1[]" />
            </div>
            <button type="button" class="add-file-button">Add Another File</button>
        </div>

        <hr>

        <!-- Second File Input Group -->
        <div class="file-input-container" data-name="files2" data-max-files="1">
            <div class="file-input">
                <input type="file" name="files2[]" />
            </div>
            <button type="button" class="add-file-button">Add Another File</button>
        </div>

        <hr>

        <!-- Third File Input Group -->
        <div class="file-input-container" data-name="files3" data-max-files="3">
            <div class="file-input">
                <input type="file" name="files3[]" />
            </div>
            <button type="button" class="add-file-button">Add Another File</button>
        </div>

        <hr>

        <!-- Fourth File Input Group -->
        <div class="file-input-container" data-name="files4" data-max-files="2">
            <div class="file-input">
                <input type="file" name="files4[]" />
            </div>
            <button type="button" class="add-file-button">Add Another File</button>
        </div>

        <hr>

        <button type="submit">Submit</button>
    </form>

    <script>
        class FileInputManager {
            constructor(container) {
                this.fileInputContainer = container;
                this.addFileButton = container.querySelector('.add-file-button');
                this.maxFileInputs = parseInt(container.getAttribute('data-max-files'), 10);
                this.init();
            }

            init() {
                this.addFileButton.addEventListener('click', () => this.addFileInput());
                this.updateButtonState();
            }

            addFileInput() {
                const currentFileInputs = this.fileInputContainer.getElementsByClassName('file-input').length;
                if (currentFileInputs < this.maxFileInputs) {
                    const newFileInput = document.createElement('div');
                    newFileInput.classList.add('file-input');
                    newFileInput.innerHTML = `<input type="file" name="${this.fileInputContainer.getAttribute('data-name')}[]" />`;
                    this.fileInputContainer.insertBefore(newFileInput, this.addFileButton);
                    this.updateButtonState();
                }
            }

            updateButtonState() {
                const currentFileInputs = this.fileInputContainer.getElementsByClassName('file-input').length;
                this.addFileButton.disabled = currentFileInputs >= this.maxFileInputs;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const fileInputContainers = document.querySelectorAll('.file-input-container');
            fileInputContainers.forEach(container => {
                new FileInputManager(container);
            });
        });
    </script>
</body>
</html>

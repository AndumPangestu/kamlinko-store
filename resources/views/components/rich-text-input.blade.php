@props(['name', 'label', 'initialValue'])

<div class="editor-container">
    <input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $initialValue }}">
    <div id="editor-{{ $name }}"></div>
</div>

<link rel="stylesheet" 
    href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css" />
<script 
    src="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const {
            AutoLink,
            Link,
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Underline,
            Font,
            Alignment,
            Paragraph,
            SimpleUploadAdapter,
            Image,
            ImageUpload,
            ImageResize,
            ImageResizeEditing,
            ImageResizeButtons,
            ImageToolbar,
            ImageStyle,
            Indent,
            IndentBlock,
            List,
            ListProperties,
            Table,
            TableToolbar
        } = CKEDITOR;

        ClassicEditor
            .create(document.querySelector('#editor-{{ $name }}'), {
                plugins: [
                    AutoLink, Link, Essentials, Bold, Italic, Underline, Font, Paragraph,
                    SimpleUploadAdapter,
                    Image, ImageUpload, ImageResize, ImageResizeEditing,
                    ImageResizeButtons, ImageToolbar, Alignment, ImageStyle, Table, TableToolbar, List,
                    ListProperties, Indent, IndentBlock
                ],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', 'underline', '|', 'alignment', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'imageUpload', '|', 'insertTable', '|', 'numberedList', 'bulletedList', '|',
                    'indent', 'outdent', '|', 'link'
                ],
                image: {
                    resizeOptions: [{
                            name: 'resizeImage:original',
                            value: null,
                            label: 'Original'
                        },
                        {
                            name: 'resizeImage:custom',
                            label: 'Custom',
                            value: 'custom'
                        },
                        {
                            name: 'resizeImage:40',
                            value: '40',
                            label: '40%'
                        },
                        {
                            name: 'resizeImage:60',
                            value: '60',
                            label: '60%'
                        }
                    ],
                    toolbar: [
                        'resizeImage', 'imageTextAlternative', '|', 'imageStyle:inline',
                        'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'
                    ],
                    styles: [
                        'full', 'alignLeft', 'alignCenter', 'alignRight'
                    ]
                },
                simpleUpload: {
                    uploadUrl: '{{ route('ckeditor.upload') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    },
                },
                link: {
                    // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
                    addTargetToExternalLinks: true,

                    // Let the users control the "download" attribute of each link.
                    decorators: [{
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'download'
                        }
                    }]
                }
            })
            .then(editor => {
                // Set the initial content from the prop
                editor.setData(`{!! $initialValue !!}`);

                editor.model.document.on('change:data', () => {
                    document.querySelector('#{{ $name }}').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
<style>
    .ck-editor__editable_inline:not(.ck-comment__input *) {
        height: 30rem;
        padding: 2rem;
        overflow-y: auto;
    }

    .ck-content {
        color: black;
    }

    .editor-container .ck-content ul,
    .editor-container .ck-content ol {
        padding-left: 20px;
    }

    .editor-container .ck-content li {
        margin: 0 0 5px 20px;
    }
</style>

const translate = window.translate;
const editor = window.editor;
const layouts = editor.layouts;
const fields = editor.fields;

editor.initializeTheme = function(options) {
    editor.setOptions(options);

    const components = [
    ];

    editor.registerComponents(components);
    editor.defineElement();
};

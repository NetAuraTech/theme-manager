const translate = window.translate;
const editor = window.editor;
const layouts = editor.layouts;
const fields = editor.fields;

editor.initializeTheme = function(options) {
    editor.setOptions(options);
    const components =
        [
            {
                _id: 'header',
                label: translate('admin.editor.sidebar.tabs.header'),
                title: translate('admin.editor.sidebar.tabs.header'),
                category: translate('admin.editor.category.template'),
                canEditAppearance: false,
                fields: [
                    fields.Repeater('links', {
                        addLabel: translate('admin.add'),
                        fields: [...editor.links()],
                        label: translate('admin.editor.sidebar.tabs.links')
                    }),
                ]
            }
        ];

    editor.registerComponents(components);
    editor.defineElement();
};
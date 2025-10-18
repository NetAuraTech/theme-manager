const translate = window.translate;
const editor = window.editor;
const layouts = editor.layouts;
const fields = editor.fields;

editor.initializeTheme = function(options) {
    editor.setOptions(options);

    const components = [
        {
            _id: 'hero',
            title: translate('theme.admin.editor.sidebar.tabs.hero'),
            category: translate('content-manager.admin.editor.category.template'),
            canEditAppearance: true,
            fields: [
                editor.titleField('title', translate('content-manager.admin.editor.sidebar.tabs.title.value')),
                editor.titleField('sub-title', translate('theme.admin.editor.sidebar.tabs.sub-title')),
                fields.HtmlText('content', {
                    label: translate('content-manager.admin.editor.sidebar.tabs.content'),
                    colors: Object.values(editor.colors()),
                    multiline: true,
                    canAnimate: true
                }),
                fields.Repeater('ctas', {
                    addLabel: translate('core-cms.admin.add'),
                    fields: [editor.ctas()],
                    label: translate('content-manager.admin.editor.sidebar.tabs.ctas')
                }),
            ]
        },
        {
            _id: 'header',
            label: translate('theme.admin.editor.sidebar.tabs.header'),
            title: translate('theme.admin.editor.sidebar.tabs.header'),
            category: translate('content-manager.admin.editor.category.template'),
            canEditAppearance: false,
            fields: [
                fields.Repeater('links', {
                    addLabel: translate('core-cms.admin.add'),
                    fields: [editor.links()],
                    label: translate('content-manager.admin.editor.sidebar.tabs.links')
                }),
            ]
        },
    ];

    editor.registerComponents(components);
    editor.defineElement();
};

const translate = window.translate;

const editor = window.editor;
const layouts = editor.layouts;
const fields = editor.fields;

editor
    .jsonFetchOrFlash('/api/posts', {
        method: 'GET',
    })
    .then(data => {
        const options = []
        data['data'].map(post => {
            options.push({
                label: `${post.type} - ${post.label}`,
                value: JSON.stringify({
                    path: post.path,
                    label: post.label,
                    slug: post.slug,
                }),
            })
        })

        const components = [
            {
                _id: 'header',
                label: translate('admin.editor.sidebar.tabs.header'),
                title: translate('admin.editor.sidebar.tabs.header'),
                category: translate('admin.editor.category.template'),
                canEditAppearance: false,
                fields: [
                    fields.Repeater('links', {
                        addLabel: translate('admin.add'),
                        fields: [...editor.links(options)],
                        label: translate('admin.editor.sidebar.tabs.links')
                    }),
                ]
            }
        ];

        editor.registerComponents(components, options);

        editor.defineElement();
    })
    .catch(console.error)

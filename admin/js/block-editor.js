/**
 * SourceHub Block Editor Extensions
 * Adds Smart Link functionality to the WordPress block editor
 */

(function() {
    'use strict';

    // Wait for WordPress to be ready
    wp.domReady(function() {
        // Register Smart Link format
        wp.richText.registerFormatType('sourcehub/smart-link', {
            title: 'Smart Link',
            tagName: 'span',
            className: 'sourcehub-smart-link',
            attributes: {
                'data-smart-url': 'data-smart-url'
            },
            edit: SmartLinkEdit
        });

        // Register Custom Smart Link format
        wp.richText.registerFormatType('sourcehub/custom-smart-link', {
            title: 'Custom Smart Link',
            tagName: 'span',
            className: 'sourcehub-custom-smart-link',
            attributes: {
                'data-custom-urls': 'data-custom-urls'
            },
            edit: CustomSmartLinkEdit
        });
    });

    // Smart Link Edit Component
    function SmartLinkEdit(props) {
        const { isActive, value, onChange, onFocus } = props;
        const { RichTextToolbarButton, RichTextShortcut } = wp.blockEditor;
        const { useState } = wp.element;
        const { Modal, TextControl, Button } = wp.components;
        const { __ } = wp.i18n;

        const [isModalOpen, setIsModalOpen] = useState(false);
        const [smartUrl, setSmartUrl] = useState('');

        // Handle applying the smart link
        const onApply = () => {
            if (!smartUrl) {
                return;
            }

            // Apply the format with the smart URL
            const newValue = wp.richText.applyFormat(value, {
                type: 'sourcehub/smart-link',
                attributes: {
                    'data-smart-url': smartUrl
                }
            });

            onChange(newValue);
            setIsModalOpen(false);
            setSmartUrl('');
        };

        // Handle removing the smart link
        const onRemove = () => {
            const newValue = wp.richText.removeFormat(value, 'sourcehub/smart-link');
            onChange(newValue);
        };

        return wp.element.createElement(
            wp.element.Fragment,
            null,
            wp.element.createElement(RichTextShortcut, {
                type: 'primary',
                character: 'l',
                onUse: () => setIsModalOpen(true)
            }),
            wp.element.createElement(RichTextToolbarButton, {
                icon: 'admin-links',
                title: __('Smart Link', 'sourcehub'),
                onClick: () => {
                    if (isActive) {
                        onRemove();
                    } else {
                        setIsModalOpen(true);
                    }
                },
                isActive: isActive,
                shortcutType: 'primary',
                shortcutCharacter: 'l'
            }),
            isModalOpen && wp.element.createElement(
                Modal,
                {
                    title: __('Add Smart Link', 'sourcehub'),
                    onRequestClose: () => setIsModalOpen(false),
                    className: 'sourcehub-smart-link-modal'
                },
                wp.element.createElement(
                    'div',
                    { className: 'sourcehub-smart-link-form' },
                    wp.element.createElement(
                        'p',
                        null,
                        __('Enter the path that will be appended to each spoke site\'s base URL. For example, entering "/weather" will create links like "spoke1.com/weather" and "spoke2.com/weather".', 'sourcehub')
                    ),
                    wp.element.createElement(TextControl, {
                        label: __('URL Path', 'sourcehub'),
                        value: smartUrl,
                        onChange: setSmartUrl,
                        placeholder: '/weather',
                        help: __('Start with "/" for absolute paths or use relative paths', 'sourcehub')
                    }),
                    wp.element.createElement(
                        'div',
                        { className: 'sourcehub-modal-actions' },
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            onClick: onApply,
                            disabled: !smartUrl
                        }, __('Apply Smart Link', 'sourcehub')),
                        wp.element.createElement(Button, {
                            isSecondary: true,
                            onClick: () => setIsModalOpen(false)
                        }, __('Cancel', 'sourcehub'))
                    )
                )
            )
        );
    }

    // Custom Smart Link Edit Component
    function CustomSmartLinkEdit(props) {
        const { isActive, value, onChange, onFocus } = props;
        const { RichTextToolbarButton, RichTextShortcut } = wp.blockEditor;
        const { useState } = wp.element;
        const { Modal, TextControl, Button } = wp.components;
        const { __ } = wp.i18n;

        const [isModalOpen, setIsModalOpen] = useState(false);
        const [customUrls, setCustomUrls] = useState({});

        // Get spoke connections from localized data
        const spokes = sourcehub_editor.spokes || [];

        // Handle applying the custom smart link
        const onApply = () => {
            // Check if at least one URL is provided
            const hasUrls = Object.values(customUrls).some(url => url && url.trim());
            if (!hasUrls) {
                return;
            }

            // Apply the format with the custom URLs
            const newValue = wp.richText.applyFormat(value, {
                type: 'sourcehub/custom-smart-link',
                attributes: {
                    'data-custom-urls': JSON.stringify(customUrls)
                }
            });

            onChange(newValue);
            setIsModalOpen(false);
            setCustomUrls({});
        };

        // Handle removing the custom smart link
        const onRemove = () => {
            const newValue = wp.richText.removeFormat(value, 'sourcehub/custom-smart-link');
            onChange(newValue);
        };

        // Handle URL change for a specific spoke
        const onUrlChange = (spokeId, url) => {
            setCustomUrls(prev => ({
                ...prev,
                [spokeId]: url
            }));
        };

        return wp.element.createElement(
            wp.element.Fragment,
            null,
            wp.element.createElement(RichTextShortcut, {
                type: 'primary',
                character: 'k',
                onUse: () => setIsModalOpen(true)
            }),
            wp.element.createElement(RichTextToolbarButton, {
                icon: 'admin-site-alt3',
                title: __('Custom Smart Link', 'sourcehub'),
                onClick: () => {
                    if (isActive) {
                        onRemove();
                    } else {
                        setIsModalOpen(true);
                    }
                },
                isActive: isActive,
                shortcutType: 'primary',
                shortcutCharacter: 'k'
            }),
            isModalOpen && wp.element.createElement(
                Modal,
                {
                    title: __('Add Custom Smart Link', 'sourcehub'),
                    onRequestClose: () => setIsModalOpen(false),
                    className: 'sourcehub-custom-smart-link-modal'
                },
                wp.element.createElement(
                    'div',
                    { className: 'sourcehub-custom-smart-link-form' },
                    wp.element.createElement(
                        'p',
                        null,
                        __('Enter a custom URL for each spoke site. The selected text will link to different URLs depending on which spoke site the content is syndicated to.', 'sourcehub')
                    ),
                    spokes.length === 0 && wp.element.createElement(
                        'p',
                        { style: { color: '#d63638' } },
                        __('No spoke connections found. Please add spoke connections first.', 'sourcehub')
                    ),
                    spokes.map(function(spoke) {
                        return wp.element.createElement(
                            'div',
                            { 
                                key: spoke.id,
                                className: 'custom-url-field'
                            },
                            wp.element.createElement(TextControl, {
                                label: spoke.name + ' (' + spoke.url + ')',
                                value: customUrls[spoke.id] || '',
                                onChange: (url) => onUrlChange(spoke.id, url),
                                placeholder: 'https://example.com/page',
                                help: __('Full URL for this spoke site', 'sourcehub')
                            })
                        );
                    }),
                    wp.element.createElement(
                        'div',
                        { className: 'sourcehub-modal-actions' },
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            onClick: onApply,
                            disabled: spokes.length === 0 || !Object.values(customUrls).some(url => url && url.trim())
                        }, __('Apply Custom Smart Link', 'sourcehub')),
                        wp.element.createElement(Button, {
                            isSecondary: true,
                            onClick: () => setIsModalOpen(false)
                        }, __('Cancel', 'sourcehub'))
                    )
                )
            )
        );
    }

})();

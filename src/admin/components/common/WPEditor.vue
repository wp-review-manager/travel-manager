<template>
    <div class="wp_vue_editor_wrapper">
        <textarea v-if="hasWpEditor && !no_tiny_mce" class="wp_vue_editor" :id="editor_id">{{modelValue}}</textarea>
        <textarea v-else
            class="wp_vue_editor wp_vue_editor_plain"
            v-model="plain_content"
            @click="updateCursorPos">
        </textarea>
    </div>
</template>
<script>
import popover from './InputPopoverDropdown'
export default{
    name: 'wp-editor',
    components: {
        popover
    },
    props: {
        editor_id: {
            type: String,
            default() {
                return 'wp_editor_'+ Date.now() + parseInt(Math.random() * 1000);
            }
        },
        modelValue: {
            type: String,
            default() {
                return '';
            }
        },
        editorShortcodes: {
            type: Array,
            default() {
                return []
            }
        },
        height: {
            type: Number,
            default() {
                return 250;
            }
        }
    },
    data() {
        return {
            hasWpEditor: !!window.wp.editor,
            plain_content: this.modelValue,
            cursorPos: this.modelValue.length,
            no_tiny_mce: false
        }
    },
    watch: {
        plain_content() {
            this.$emit('update:modelValue', this.plain_content);
        },
    },
    methods: {
        initEditor() {
            wp.editor.remove(this.editor_id);
            const that = this;
            wp.editor.initialize(this.editor_id, {
                mediaButtons: true,
                tinymce: {
                    height  : that.height,
                    plugins : 'image,media,lists,link',
                    toolbar1: 'formatselect,customInsertButton,table,bold,italic,bullist,numlist,link,blockquote,alignleft,aligncenter,alignright,underline,strikethrough,forecolor,removeformat,codeformat,outdent,indent,undo,redo,image,media',
                    setup(ed) {
                        ed.on('change', function (ed, l) {
                            that.changeContentEvent();
                        });
                        if (!that.buttonInitiated) {
                            that.buttonInitiated = true;
                            ed.addButton('customInsertButton', {
                                text: 'Button',
                                classes: 'wpns_editor_btn',
                                onclick() {
                                    that.showInsertButtonModal(ed);
                                }
                            });
                        }
                    }
                },
                quicktags: true
            });

            jQuery('#'+this.editor_id).on('change', function(e) {
                that.changeContentEvent();
            });
        },
        changeContentEvent() {
            let content = wp.editor.getContent(this.editor_id);
            this.$emit('update:modelValue', content);
        },

        handleCommand(command) {
            if(this.hasWpEditor) {
                tinymce.activeEditor.insertContent(command);
            } else {
                var part1 = this.plain_content.slice(0, this.cursorPos);
                var part2 = this.plain_content.slice(this.cursorPos, this.plain_content.length);
                this.plain_content = part1 + command + part2;
                this.cursorPos += command.length;
            }
        },
        showInsertButtonModal(editor) {
            this.currentEditor = editor;
            this.showButtonDesigner = true;
        },
        insertHtml(content) {
            this.currentEditor.insertContent(content);
        },
        updateCursorPos() {
            var cursorPos = jQuery('.wp_vue_editor_plain').prop('selectionStart');
            this.$set(this, 'cursorPos', cursorPos);
        }
    },
    mounted() {
        if(this.hasWpEditor) {
            this.initEditor();
        }
    }
}
</script>

<style lang="scss">
    .wp_vue_editor {
        width: 100%;
        min-height: 100px;
    }

    .wp_vue_editor.wp_vue_editor_plain.el-textarea {
        margin-top: 30px;
    }

    .wp_vue_editor_wrapper {
        position: relative;

        .popover-wrapper {
            z-index: 2;
            position: absolute;
            top: -6px;
            right: 0;

            &-plaintext {
                left: auto;
                right: 0;
                top: -32px;
            }
        }
        .wp-editor-tabs {
            float: left;
            button.wp-switch-editor {
                line-height: 2px;
            }
        }
    }
</style>

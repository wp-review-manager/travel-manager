<template>
    <div>
        <el-popover
            class="wpf-popover"
            ref="input-popover1"
            placement="right"
            popper-class="el-dropdown-list-wrapper"
            v-model="visible"
            trigger="click">
            <div class="el_pop_data_group">
                <div  class="el_pop_data_headings">
                    <ul>
                        <li
                            v-for="(item,item_index) in data"
                            :data-item_index="item_index"
                            :class="(activeIndex == item_index) ? 'active_item_selected' : ''"
                            @click="activeIndex = item_index" :key="item_index">
                            {{item.title}}
                        </li>
                    </ul>
                </div>
                <div class="el_pop_data_body">
                    <ul v-for="(item,current_index) in data" v-show="activeIndex == current_index" :class="'el_pop_body_item_'+current_index" :key="current_index">
                        <li v-if="item.shortcodes.length === 0"><span style="font-size: medium">No Item To Select</span></li>
                        <li v-else @click="insertShortcode(code.tag)" v-for="code in item.shortcodes" :key="code.tag">{{code.label}} <span>{{code.tag}}</span></li>
                    </ul>
                </div>
            </div>
        </el-popover>
        <el-button :class="btnType === 'text' ? '' : 'pop-over-btn'"
            v-popover:input-popover1
            :type="btnType"
            :v-html="buttonText"
        />
    </div>
</template>

<script>
    export default {
        name: 'inputPopoverDropdown',
        props: {
            data: Array,
            close_on_insert: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            buttonText: {
                type: String,
                default() {
                    return 'Add Shortcodes <i class="el-icon-arrow-down el-icon--right"></i>';
                }
            },
            btnType: {
                type: String,
                default() {
                    return 'success';
                }
            }
        },
        data() {
          return {
              activeIndex: 0,
              visible: false
          }
        },
        methods: {
            insertShortcode(code) {
                this.$emit('command', code);
                if(this.close_on_insert) {
                    this.visible = false;
                }
            }
        },
        mounted() {
        }
    }
</script>

<style lang="scss">

    .el-popover{
        top: 48px !important;
    }
    .el-dropdown-list-wrapper {
        padding: 0;
        width: fit-content !important;
        .group-title {
            display: block;
            padding: 5px 10px;
            background-color: gray;
            color: #fff;
        }
    }

    .input-textarea-value {
        position: relative;

        .icon {
            position: absolute;
            right: 0;
            top: -18px;
            cursor: pointer;
        }
    }
    .el_pop_data_group {
        background: #6c757d;
        overflow: hidden;
        display: flex;
        .el_pop_data_headings {
            min-width: 170px;
            float: left;
            ul {
                padding: 0;
                margin: 10px 0px;
                li {
                    color: white;
                    padding: 5px 10px 5px 10px;
                    display: block;
                    margin-bottom: 0px;
                    border-bottom: 1px solid #949393;
                    cursor: pointer;
                    &.active_item_selected {
                        background: whitesmoke;
                        color: #6c757d;
                        border-left: 2px solid #6c757d;
                    }
                }
            }
        }

        .el_pop_data_body {
            float: left;
            background: whitesmoke;
            width: 350px;
            ul {
                padding: 10px 0;
                margin: 0;
                li {
                    color: black;
                    padding: 5px 10px 5px 10px;
                    display: block;
                    margin-bottom: 0px;
                    border-bottom: 1px dotted #dadada;
                    cursor: pointer;
                    text-align: left;
                    &:hover {
                        background: white;
                    }
                    span {
                        font-size: 11px;
                        color: #8e8f90;
                    }
                }
            }
        }
    }
</style>
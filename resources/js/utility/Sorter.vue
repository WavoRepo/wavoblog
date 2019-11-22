<template>
    <div class="sorter">
        <span v-show="sorted.up === '' || sorted.up == true" class="sorter-icon" :style="isCenter('up')">
            <i class="fa fa-caret-up" @click="sort('asc')"></i>
        </span>
        <span v-show="sorted.down === '' || sorted.down === true" class="sorter-icon" :style="isCenter('down')">
            <i class="fa fa-caret-down" @click="sort('desc')"></i>
        </span>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default {
        name: 'sorter',
        props: ['sorting', 'sortby', 'sortDir'],
        data () {
            return {
                sorted: {
                    up: '',
                    down: ''
                }
            }
        },
        watch: {
            sortDir: function ($dir) {
                this.changeSorter($dir);
            }
        },
        methods: {
            isCenter ($dir) {
                if(!this.sorting || this.sorting != this.sortby) {
                    this.sorted.up = '';
                    this.sorted.down = '';
                }

                if(this.sorting == this.sortby) {
                    if(this.sortDir == 'desc') {
                        this.sorted.up = true;
                        this.sorted.down = false;
                    }
                    if(this.sortDir == 'asc') {
                        this.sorted.up = false;
                        this.sorted.down = true;
                    }
                }

                if($dir == 'up') {
                    if(this.sorted.up !== true) return 'top: -7px';
                }
                if($dir == 'down') {
                    if(this.sorted.down !== true) return 'top: 6px';
                }

                return 'top: 0px';
            },
            changeSorter ($type) {
                if ($type == 'asc') {
                    this.sorted.up = false;
                    this.sorted.down = true;
                }
                else {
                    this.sorted.up = true;
                    this.sorted.down = false;
                }
            },
            sort ($type) {
                this.changeSorter($type);

                this.$emit('sortBy', {
                    by: this.sortby,
                    type: $type
                })
            },
        },

    }
</script>

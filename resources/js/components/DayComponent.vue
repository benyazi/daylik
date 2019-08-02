<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xs-12">
                <div class="card" v-if="currentDay">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <button class="btn btn-light w-100" @click="prev()">
                                    <
                                </button>
                            </div>
                            <div class="col-6 text-center font-weight-bold">
                                <span>{{currentDay.title}}</span><br>
                                <span>{{currentDay.stat.success}} / {{currentDay.stat.count}}</span>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" @click="next()">
                                    >
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                            <tr v-for="task in currentDay.tasks" v-show="!task.forRemove" class="b_task_status" :class="{'b_task_status--wait':(task.status=='wait'),'b_task_status--done':(task.status=='done'),'b_task_status--fail':(task.status=='fail')}">
                                <td>
                                    {{task.title}}
                                </td>
                                <td>
                                    {{task.status}}
                                </td>
                                <td>
                                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-success" @click="setStatus(task, 'done')">Done</button>
                                        <button type="button" class="btn btn-light" @click="setStatus(task, 'wait')">Wait</button>
                                        <button type="button" class="btn btn-danger" @click="setStatus(task, 'fail')">Fail</button>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-danger w-100" @click="remove(task)">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input class="form-control" v-model="newTask" v-on:keyup.enter="add()">
                                </td>
                                <td colspan="2">
                                    <button class="btn btn-primary w-100" @click="add()">Add</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .b_task_status {}
    .b_task_status--wait {
        background-color: transparent;
    }
    .b_task_status--done {
        background-color: #38c172;
        color: #fff;
    }
    .b_task_status--fail {
        background-color: #e3342f;
        color: #fff;
    }
</style>
<script>
    export default {
        data() {
            return {
                isLoading: false,
                currentDay: null,
                newTask: null,
            };
        },
        computed: {
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData(date) {
                if(this.isLoading) {
                    return;
                }
                this.isLoading = true;
                let url = '/getDayData';
                if(date !== undefined) {
                    url = url + '?date=' + date;
                }
                axios.get(url)
                    .then(response => {
                        this.$set(this, 'currentDay', response.data.userDay);
                        // this.currentDay = ;
                    }).finally(() => {
                    this.isLoading = false;
                });
            },
            saveData() {
                if(this.isLoading) {
                    return;
                }
                this.isLoading = true;
                axios.post('/saveDayData', {userDay:this.currentDay})
                    .then(response => {
                        this.$set(this, 'currentDay', response.data.userDay);
                    }).finally(() => {
                    this.isLoading = false;
                });
            },
            next() {
                let date = this.currentDay.nav.next;
                this.getData(date);
            },
            prev() {
                let date = this.currentDay.nav.prev;
                this.getData(date);
            },
            add() {
                this.currentDay.tasks.push({
                    'day_id': this.currentDay.id,
                    'title': this.newTask,
                    'status': 'wait',
                    'type': 'plan'
                });
                this.newTask = null;
                this.saveData();
            },
            remove(task) {
                task.forRemove = 1;
                this.saveData();
            },
            setStatus(task, status) {
                task.status = status;
                this.saveData();
            }
        }
    }
</script>

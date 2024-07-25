<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="text-light float-start">{{ title }}</h5>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" v-model="searchQuery" placeholder="Search tasks"
                            @input="getTasks">
                    </div>
                    <div class="col-md-4">
                        <button @click="createTask" class="btn btn-success btn-sm float-end">New Task</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select" v-model="filter" @change="applyFilter">
                            <option value="all">All Tasks</option>
                            <option value="not_deleted">Not Deleted</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" v-model="selectedCategory" @change="getTasks">
                            <option value="all">All Categories</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" v-model="selectedStatus" @change="getTasks">
                            <option value="all">All Statuses</option>
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Overdue">Overdue</option>
                            <option value="Deleted">Deleted</option>
                        </select>
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Date & Time</th>
                            <th>Description</th>
                            <!-- <th>Sub Tasks Count</th> -->
                            <!-- <th>Sub Tasks</th> -->
                            <th>Category</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody v-show="filteredTasks">
                        <tr v-for="(task, index) in filteredTasks" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ task.title }}</td>
                            <td>{{ task.date }} | {{ task.time }}</td>
                            <td>{{ task.description.length <= 10 ? task.description : task.description.substr(0, 10)
                                + '...' }} </td>
                                    <!-- <td>
                                <span :class="`badge ${task.sub_tasks.length == 0 ? 'bg-warning' : 'bg-success'} text-dark`">
                                    {{ task.sub_tasks.length }} Sub Tasks
                                </span>
                            </td>
                            <td>
                                <button @click="showSubTasks(task)" class="btn btn-info btn-sm mx-1">Sub Tasks</button>
                            </td> -->
                            <td>
                                <span :class="`badge text-dark`" v-if="task.category_id">
                                    {{ task.category.name }}
                                </span>
                                <span :class="`badge text-dark`" v-if="!task.category_id">
                                    Not Categorized
                                </span>
                            </td>
                            <td>
                                <span :class="`badge bg-success text-dark`" v-show="task.status == 'Completed'">
                                    {{ task.status }}
                                </span>
                                <span :class="`badge bg-warning text-dark`" v-show="task.status == 'Pending'">
                                    {{ task.status }}
                                </span>
                                <span :class="`badge bg-danger text-dark`" v-show="task.status == 'Overdue'">
                                    {{ task.status }}
                                </span>
                                <span :class="`badge bg-danger text-dark`" v-show="task.status == 'Deleted'">
                                    {{ task.status }}
                                </span>
                            </td>
                            <td>{{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'N/A' }}</td>
                            <td class="text-center">
                                <button @click="editTask(task)" class="btn btn-primary btn-sm m-1">Edit</button>
                                <button @click="removeTask(task)" class="btn btn-danger btn-sm m-1"
                                    v-show="task.deleted == 0">Delete</button>
                                <button @click="retrieveTask(task)" class="btn btn-secondary btn-sm m-1"
                                    v-show="task.deleted == 1">Retrieve</button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-show="!filteredTasks">
                        <tr>
                            <td colspan="6">
                                <h3>No Tasks Here</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Pagination Controls -->
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="pagination d-block">
                                <button class="btn btn-secondary" @click="prevPage"
                                    :disabled="currentPage === 1">Previous</button>
                                <span class="m-2">Page {{ currentPage }} of {{ totalPages }}</span>
                                <button class="btn btn-secondary" @click="nextPage"
                                    :disabled="currentPage === totalPages">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div :class="`modal-dialog modal-dialog-centered ${!deleteMode ? 'modal-lg' : 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="taskModalLabel" v-show="!deleteMode">{{ !editMode ? 'Create New Task' :
                        'Update Task' }}</h1>
                    <h1 class="modal-title" id="taskModalLabel" v-show="deleteMode">Delete Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="!deleteMode">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="title">Title</div>
                                <input type="text" class="form-control" placeholder="Enter title"
                                    v-model="taskData.title">
                                <span class="text-danger" v-show="taskErrors.title">Title is required</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="date">Date</div>
                                <input type="date" class="form-control" v-model="taskData.date">
                                <span class="text-danger" v-show="taskErrors.date">Date is required</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="time">Time</div>
                                <input type="time" class="form-control" v-model="taskData.time">
                                <span class="text-danger" v-show="taskErrors.time">Time is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-show="!deleteMode">
                        <div class="col-md-12">
                            <label for="description">Description</label>
                            <textarea class="form-control" rows="3" v-model="taskData.description"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3" v-show="!deleteMode">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control" v-model="taskData.due_date">
                            </div>
                        </div>
                        <div class="col-md-3" v-show="shouldShowStatusDiv">
                            <label for="status">Status</label>
                            <select class="form-control" v-model="taskData.status">
                                <option value="" disabled>Select Status</option>
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="Overdue">Overdue</option>
                                <option value="Deleted">Deleted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="category">Category</label>
                            <select class="form-control" v-model="taskData.category_id">
                                <option value="" disabled>Select Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" @click="createCategory" class="btn btn-secondary mt-4">Add New
                                Category</button>
                        </div>
                    </div>
                    <h5 class="text-center" v-show="deleteMode">Are you sure you want to delete this task?</h5>
                </div>
                <div class="modal-footer" v-show="!deleteMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="!editMode ? storeTask() : updateTask(task)">{{
                        !editMode ? 'Create Task' : 'Save Changes' }}</button>
                </div>
                <div class="modal-footer" v-show="deleteMode">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="deleteTask">Delete Task</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Task Modal -->
    <!-- Sub Task Modal -->
    <div class="modal fade" id="subTaskModal" tabindex="-1" aria-labelledby="subTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="subTaskModalLabel">Sub Tasks</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(subTask, index) in sub_tasks" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ subTask.title }}</td>
                                    <td>{{ subTask.description }}</td>
                                    <td>
                                        <span
                                            :class="`badge ${subTask.status == 0 ? 'bg-warning' : 'bg-success'} text-dark`">
                                            {{ subTask.status == 0 ? 'Incompleted' : 'Completed' }}
                                        </span>
                                    </td>
                                    <td>{{ subTask.start_date }}</td>
                                    <td>{{ subTask.end_date }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm mx-1"
                                            @click="editSubTask(subTask)">Edit</button>
                                        <button class="btn btn-danger btn-sm mx-1" v-if="!subEditMode"
                                            @click="deleteSubTask(subTask)">Delete</button>
                                        <button class="btn btn-success btn-sm mx-1" v-if="subTask.status == 0"
                                            @click="markAsComplete(subTask)">Mark As Complete</button>
                                        <button class="btn btn-warning btn-sm mx-1" v-if="subTask.status == 1"
                                            @click="markAsIncomplete(subTask)">Mark As Incomplete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3 mb-3" v-for="(subTask, index) in subTaskData" :key="index">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" v-model="subTask.title">
                                <span class="text-danger" v-show="subTaskErrors[index].title">Title is required</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" v-model="subTask.description">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" v-model="subTask.start_date">
                                <span class="text-danger" v-show="subTaskErrors[index].start_date">Start Date is
                                    required</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" v-model="subTask.end_date">
                                <span class="text-danger" v-show="subTaskErrors[index].end_date">End Date is
                                    required</span>
                            </div>
                        </div>
                        <div class="col-md-1" v-if="!subEditMode">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button @click="addSubTaskData" class="btn btn-success btn-sm mt-4">
                                            <h6>&plus;</h6>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button @click="removeSubTaskData(index)"
                                            v-if="subTaskData.length > 1 && index > 0"
                                            class="btn btn-danger btn-sm mt-4">
                                            <h6>&times;</h6>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-info btn-sm float-end m-2"
                            @click="!subEditMode ? storeSubTask() : updateSubTask()">
                            {{ !subEditMode ? 'Store Sub Task' : 'Update Sub Task' }}
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sub Task Modal-->
    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div :class="`modal-dialog modal-dialog-centered ${!deleteMode ? 'modal-lg' : 'modal-sm'}`">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="categoryModalLabel">Add New Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="category">Category</div>
                                <input type="text" class="form-control" placeholder="Enter Category Name"
                                    v-model="categoryData.name">
                                <span class="text-danger" v-show="categoryErrors.name">Category Name is required</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="storeCategory">Create Category</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Category Modal -->
</template>

<script>
export default {
    setup: () => ({
        title: 'All Tasks'
    }),
    data() {
        return {
            filter: 'all',
            selectedCategory: 'all',
            selectedStatus: 'all',
            searchQuery: '',
            perPage: 5, // Items per page
            currentPage: 1, // Current page
            totalPages: 1, // Total number of pages
            editMode: false,
            subEditMode: false,
            deleteMode: false,
            categoryData: {
                name: ''
            },
            categoryErrors: {
                name: false
            },
            taskData: {
                id: '',
                title: '',
                date: '',
                time: '',
                description: '',
                category_id: null,
                status: '',
                due_dates: '',
                deleted: 0
            },
            taskErrors: {
                title: false,
                date: false,
                time: false
            },
            tasks: [],
            sub_tasks: {},
            subTaskData: [],
            subTaskErrors: [],
            categories: [],
        }
    },
    computed: {
        filteredTasks() {
            return this.tasks.filter(task => {
                // Filter by deletion status
                if (this.filter === 'deleted') {
                    return task.deleted === 1;
                } else if (this.filter === 'not_deleted') {
                    return task.deleted === 0;
                }

                // Filter by category
                if (this.selectedCategory !== 'all' && task.category_id !== this.selectedCategory) {
                    return false;
                }

                // Filter by status
                if (this.selectedStatus !== 'all') {
                    return task.status === this.selectedStatus;
                }

                return true; // Show all tasks if no specific filter
            });
        },
        shouldShowStatusDiv() {
            return this.editMode && this.taskData.status !== 'Deleted';
        }
    },
    watch: {
        selectedCategory() {
            this.getTasks();
        },
        selectedStatus() {
            this.getTasks();
        },
        filter() {
            this.getTasks();
        }
    },
    mounted() {
        this.getTasks();
        this.fetchCategories();
    },
    methods: {
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.getTasks();
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.getTasks();
            }
        },
        applyFilter() {
            this.getTasks();
        },
        fetchCategories() {
            axios.get('/api/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        },
        createCategory() {
            $('#categoryModal').modal('show');
        },
        storeCategory() {
            this.categoryData.name == '' ? this.categoryErrors.name = true : this.categoryErrors.name = false;

            if (this.categoryData.name) {
                axios.post('/api/category/store', this.categoryData)
                    .then(response => {
                        this.fetchCategories();
                    })
                    .catch(errors => {
                        console.log(errors);
                    }).finally(() => {
                        $('#categoryModal').modal('hide');
                    });
            }
        },
        getTasks() {
            const params = {
                category_id: this.selectedCategory !== 'all' ? this.selectedCategory : undefined,
                filter: this.selectedStatus !== 'all' ? this.selectedStatus : undefined,
                search: this.searchQuery ? this.searchQuery : undefined,
                per_page: this.perPage,
                page: this.currentPage,
            };
            const t = this;
            window.emitter.emit('changeLoaderStatus', true);
            setTimeout(function () {
                axios.get('/api/tasks', { params })
                    .then(response => {
                        t.tasks = response.data.data;
                        t.currentPage = response.data.current_page;
                        t.totalPages = response.data.last_page;
                        window.emitter.emit('changeLoaderStatus', false);
                    })
                    .catch(errors => {
                        console.log(errors);
                        window.emitter.emit('changeLoaderStatus', false);
                    });
            }, 1080);
        },
        updateTask(task) {
            this.taskData.title == '' ? this.taskErrors.title = true : this.taskErrors.title = false;
            this.taskData.date == '' ? this.taskErrors.date = true : this.taskErrors.date = false;
            this.taskData.time == '' ? this.taskErrors.time = true : this.taskErrors.time = false;

            if (this.taskData.title && this.taskData.date && this.taskData.time) {
                axios.put('/api/task/' + this.taskData.id, this.taskData)
                    .then(response => {
                        console.log(response);
                        this.getTasks();
                    })
                    .catch(errors => {
                        console.log(errors);
                    }).finally(() => {
                        $('#taskModal').modal('hide');
                    });
            }
        },
        editTask(task) {
            this.deleteMode = false;
            this.editMode = true;
            this.taskData = {
                id: task.id,
                title: task.title,
                date: task.date,
                time: task.time,
                description: task.description,
                category_id: task.category_id,
                status: task.status,
                due_date: task.due_date
            }
            this.taskErrors = {
                title: false,
                date: false,
                time: false
            }
            $('#taskModal').modal('show');
        },
        createTask() {
            this.deleteMode = false;
            this.editMode = false;
            this.taskData = {
                id: '',
                title: '',
                date: '',
                time: '',
                description: '',
                category_id: null,
                status: '',
                due_date: ''
            }
            this.taskErrors = {
                title: false,
                date: false,
                time: false
            }
            $('#taskModal').modal('show');
        },
        storeTask() {
            this.taskData.title == '' ? this.taskErrors.title = true : this.taskErrors.title = false;
            this.taskData.date == '' ? this.taskErrors.date = true : this.taskErrors.date = false;
            this.taskData.time == '' ? this.taskErrors.time = true : this.taskErrors.time = false;
            this.taskData.status == '' ? this.taskData.status = 'pending' : this.taskData.status;

            if (this.taskData.title && this.taskData.date && this.taskData.time) {
                axios.post('/api/task/store', this.taskData)
                    .then(response => {
                        this.getTasks();
                    })
                    .catch(errors => {
                        console.log(errors);
                    }).finally(() => {
                        $('#taskModal').modal('hide');
                    });
            }
        },
        removeTask(task) {
            this.deleteMode = true;
            this.editMode = false;
            this.taskData.id = task.id;
            $('#taskModal').modal('show');
        },
        retrieveTask(task) {
            this.taskData.id = task.id;
            axios.post('/api/task/retrieve/' + this.taskData.id)
                .then(response => {
                    this.taskData.deleted = 0;
                    this.taskData.status = 'Pending';
                    this.getTasks();
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        deleteTask() {
            axios.delete('/api/task/' + this.taskData.id)
                .then(response => {
                    this.taskData.deleted = 1;
                    this.taskData.status = 'Deleted';
                    this.getTasks();
                })
                .catch(errors => {
                    console.log(errors);
                }).finally(() => {
                    $('#taskModal').modal('hide');
                });
        },
        showSubTasks(task) {
            this.subEditMode = false;
            this.sub_tasks = task.sub_tasks;
            this.subTaskData = [{
                id: '',
                task_id: task.id,
                title: '',
                description: '',
                start_date: '',
                end_date: '',
            }];
            this.subTaskErrors = [{
                title: false,
                start_date: false,
                end_date: false,
            }];
            $('#subTaskModal').modal('show');
        },
        addSubTaskData() {
            this.subTaskData.push({
                id: '',
                task_id: this.subTaskData[0].task_id,
                title: '',
                description: '',
                start_date: '',
                end_date: '',
            });
            this.subTaskErrors.push({
                title: false,
                start_date: false,
                end_date: false,
            });
        },
        removeSubTaskData(index) {
            this.subTaskData.splice(index, 1)
            this.subTaskErrors.splice(index, 1)
        },
        storeSubTask() {
            for (let i = 0; i < this.subTaskData.length; i++) {
                this.subTaskData[i].title == '' ? this.subTaskErrors[i].title = true : this.subTaskErrors[i].title = false
                this.subTaskData[i].start_date == '' ? this.subTaskErrors[i].start_date = true : this.subTaskErrors[i].start_date = false
                this.subTaskData[i].end_date == '' ? this.subTaskErrors[i].end_date = true : this.subTaskErrors[i].end_date = false
            }

            const result = this.subTaskData.every(subTask => {
                return subTask.title && subTask.start_date && subTask.end_date
            });

            if (result) {
                window.emitter.emit('loaderStatus', true);
                const t = this;
                setTimeout(function () {
                    axios.post('api/task/' + t.subTaskData[0].task_id + '/subTask/store', t.subTaskData)
                        .then(response => {
                            t.getTasks()
                            $('#subTaskModal').modal('hide');
                            window.emitter.emit('loaderStatus', false);
                        })
                        .catch(errors => {
                            console.log(errors)
                            window.emitter.emit('loaderStatus', false);
                        })
                }, 500);
            }
        },
        editSubTask(subTask) {
            this.subEditMode = true;
            this.subTaskData = [{
                id: subTask.id,
                task_id: subTask.task_id,
                title: subTask.title,
                description: subTask.description,
                start_date: subTask.start_date,
                end_date: subTask.end_date,
            }];
            this.subTaskErrors = [{
                title: false,
                start_date: false,
                end_date: false,
            }];
        },
        updateSubTask() {
            for (let i = 0; i < this.subTaskData.length; i++) {
                this.subTaskData[i].title == '' ? this.subTaskErrors[i].title = true : this.subTaskErrors[i].title = false
                this.subTaskData[i].start_date == '' ? this.subTaskErrors[i].start_date = true : this.subTaskErrors[i].start_date = false
                this.subTaskData[i].end_date == '' ? this.subTaskErrors[i].end_date = true : this.subTaskErrors[i].end_date = false
            }

            const result = this.subTaskData.every(subTask => {
                return subTask.title && subTask.start_date && subTask.end_date
            });

            if (result) {
                window.emitter.emit('loaderStatus', true);
                const t = this;
                setTimeout(function () {
                    axios.put('api/task/' + t.subTaskData[0].task_id + '/subTask/' + t.subTaskData[0].id, t.subTaskData[0])
                        .then(response => {
                            t.getTasks()
                            t.sub_tasks = response.data
                            window.emitter.emit('loaderStatus', false);
                        })
                        .catch(errors => {
                            console.log(errors)
                            window.emitter.emit('loaderStatus', false);
                        })
                        .finally(() => {
                            t.subEditMode = false;
                            t.subTaskData = [{
                                id: '',
                                task_id: t.subTaskData[0].task_id,
                                title: '',
                                description: '',
                                start_date: '',
                                end_date: '',
                            }];
                            t.subTaskErrors = [{
                                title: false,
                                start_date: false,
                                end_date: false,
                            }];
                        });
                }, 500);
            }
        },
        deleteSubTask(subTask) {
            if (confirm('Are you sure you want to delete this sub task?')) {
                window.emitter.emit('loaderStatus', true);
                const t = this;
                setTimeout(function () {
                    axios.delete('api/task/' + subTask.task_id + '/subTask/' + subTask.id, t.subTaskData)
                        .then(response => {
                            t.getTasks()
                            t.sub_tasks = response.data
                            window.emitter.emit('loaderStatus', false);
                        })
                        .catch(errors => {
                            console.log(errors)
                            window.emitter.emit('loaderStatus', false);
                        });
                }, 500);
            }
        },
        markAsComplete(subTask) {
            window.emitter.emit('loaderStatus', true);
            const t = this;
            setTimeout(function () {
                axios.put('api/task/' + subTask.task_id + '/subTask/' + subTask.id + '/markAsComplete', t.subTaskData)
                    .then(response => {
                        t.getTasks()
                        t.sub_tasks = response.data
                        window.emitter.emit('loaderStatus', false);
                    })
                    .catch(errors => {
                        console.log(errors)
                        window.emitter.emit('loaderStatus', false);
                    });
            }, 500);
        },
        markAsIncomplete(subTask) {
            window.emitter.emit('loaderStatus', true);
            const t = this;
            setTimeout(function () {
                axios.put('api/task/' + subTask.task_id + '/subTask/' + subTask.id + '/markAsIncomplete', t.subTaskData)
                    .then(response => {
                        t.getTasks()
                        t.sub_tasks = response.data
                        window.emitter.emit('loaderStatus', false);
                    })
                    .catch(errors => {
                        console.log(errors)
                        window.emitter.emit('loaderStatus', false);
                    });
            }, 500);
        }
    }
}
</script>

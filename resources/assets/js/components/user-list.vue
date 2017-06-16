<template>
    <div id="users-list" class="container-fluid">
        <div class="top-options row">
            <div class="col-xs-12 col-sm-8">
                <button class="btn btn-primary" data-toggle="modal" data-target="#new-user-modal">
                    <i class="fa fa-user-plus"></i>
                    <span>Create User</span>
                </button>

                <span class="btn-group">
                    <button class="btn btn-default" :disabled="selected_users.length == 0" @click="showChangePwModal()">
                        <i class="fa fa-key"></i>
                    </button>

                    <button class="btn btn-default" :disabled="selected_users.length == 0" @click="deleteUsers">
                        <i class="fa fa-trash"></i>
                    </button>
                </span>

                <button class="btn btn-primary" data-toggle="modal" data-target="#add-role-modal">
                    <i class="fa fa-plus"></i>
                    <span>Create Role</span>
                </button>
            </div>

            <div class="col-xs-12 col-sm-4 text-right">
                <input type="text" class="form-control" placeholder="Search" v-model="data.filter">
            </div>
        </div>

        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th class="hidden-xs text-center">Password</th>
                        <th class="hidden-xs">Roles</th>
                        <th class="hidden-xs"></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(user,index) in filtered_users">
                        <tr @click="toggleSelected(user)">
                            <td @click.stop="toggleDetails(user)">
                                <i class="fa fa-caret-down hidden visible-xs" v-if="expanded.includes(user.id)"></i>
                                <i class="fa fa-caret-right hidden visible-xs" v-else></i>
                                <i class="fa fa-check-square-o" @click.stop="toggleSelected(user)" v-if="user.selected"></i>
                                <i class="fa fa-square-o" @click.stop="toggleSelected(user)" v-else></i>
                            </td>
                            <td>{{ user.name  }}</td>
                            <td>{{ user.email  }}</td>
                            <td class="change-password hidden-xs text-center">
                                <span @click.stop="showChangePwModal(user.id)">Change</span>
                            </td>
                            <td class="hidden-xs roles">
                                <label v-for="role in data.roles" @click.stop="toggleUserRole({user,role})">
                                    <i class="fa fa-check-square" v-if="user.roles.map(user_role => { return user_role.id}).includes(role.id)"></i>
                                    <i class="fa fa-square-o" v-else></i>
                                    {{ role.role }}
                                </label>
                            </td>
                            <td class="text-right hidden-xs">
                                <button class="btn btn-primary" :class="{ invisible: !userUpdated(user.id) }" @click.stop="saveUserRoles(user.id)">
                                    <i class="fa fa-save"></i>
                                    <span>Save</span>
                                </button>
                            </td>
                        </tr>

                        <tr class="hidden visible-xs details" @click="toggleSelected(user)">
                            <td></td>
                            <td colspan="500" v-show="expanded.includes(user.id)">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div v-for="role in data.roles" @click.stop="toggleUserRole({user,role})">
                                            <label>
                                                <i class="fa fa-check-square" v-if="user.roles.map(user_role => { return user_role.id}).includes(role.id)"></i>
                                                <i class="fa fa-square-o" v-else></i>
                                                {{ role.role }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 buttons text-right">
                                        <div>
                                            <button class="btn btn-default" @click.stop="showChangePwModal(user.id)">
                                                <i class="fa fa-lock"></i>
                                                <span>Password</span>
                                            </button>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary" v-show="userUpdated(user.id)" @click.stop="saveUserRoles(user.id)">
                                                <i class="fa fa-save"></i>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- FORM ERRORS -->
        <div v-for="error in errors" class="alert alert-danger">
            {{ error }}
        </div>

        <!-- FORM MESSAGES -->
        <div v-for="message in messages" class="alert alert-info">
            {{ message }}
        </div>

        <!-- START NEW USER MODAL -->
        <div class="modal fade" id="new-user-modal" ref="new_user_modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title pull-left">Create User</h5>
                        <i class="fa fa-times close" data-dismiss="modal"></i>
                    </div>

                    <div class="modal-body">
                        <form class="form">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="new_user.name" />
                            </div>

                            <div class="form-group">
                                <label>E-Mail</label>
                                <input type="email" class="form-control" v-model="new_user.email" />
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" v-model="new_user.password" />
                            </div>

                            <div class="form-group">
                                <label>Re-Type Password</label>
                                <input type="password" class="form-control" v-model="new_user.password_confirmation" />
                            </div>
                        </form>

                        <div v-for="error in new_user.errors" class="alert alert-danger">
                            {{ error }}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div>
                            <button class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-ban"></i>
                                <span>Cancel</span>
                            </button>
                            <button class="btn btn-primary" @click="createNewUser">
                                <i class="fa fa-save"></i>
                                <span>Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END NEW USER MODAL -->

        <!-- START CHANGE PASSWORD MODAL -->
        <div class="modal fade" id="change-pw-modal" ref="change_pw_modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title pull-left">Change Password</h5>
                        <i class="fa fa-times close" data-dismiss="modal"></i>
                    </div>

                    <div class="modal-body">
                        <form class="form">

                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" v-model="change_pw.password" />
                            </div>

                            <div class="form-group">
                                <label>Re-Type Password</label>
                                <input type="password" class="form-control" v-model="change_pw.password_confirmation" />
                            </div>
                        </form>

                        <div v-for="error in change_pw.errors" class="alert alert-danger">
                            {{ error }}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div>
                            <button class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-ban"></i>
                                <span>Cancel</span>
                            </button>
                            <button class="btn btn-primary" @click="changePassword">
                                <i class="fa fa-save"></i>
                                <span>Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CHANGE PASSWORD MODAL -->

        <!-- START ADD ROLE MODAL -->
        <div class="modal fade" id="add-role-modal" ref="add_role_modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title pull-left">Add Role</h5>
                        <i class="fa fa-times close" data-dismiss="modal"></i>
                    </div>

                    <div class="modal-body">
                        <form class="form">
                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" class="form-control" v-model="add_role.role" />
                            </div>
                        </form>

                        <div v-for="error in add_role.errors" class="alert alert-danger">
                            {{ error }}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div>
                            <button class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-ban"></i>
                                <span>Cancel</span>
                            </button>
                            <button class="btn btn-primary" @click="addRole">
                                <i class="fa fa-save"></i>
                                <span>Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ADD ROLE MODAL -->

    </div>

</template>

<style lang="scss" scoped>
    @import '../../sass/_variables.scss';

    #users-list {
        .top-options {
            margin-top: -25px;
            background-color: darken($body-bg,10%);
            padding: 25px;

            > div:first-child {
                > *:first-child {
                    margin-right: 50px;
                }

                > *:last-child {
                    margin-left: 50px;
                }
            }
        }

        i.fa-square-o, i.fa-check-square {
            width: 13px;
        }

        td.roles {
            > *:not(:first-child) {
                margin-left: 5px;
            }
        }
        .change-password {
            span {
                zoom: 0.75;
                vertical-align: middle;
                cursor: pointer;
                color: $blue;

                &:hover, &:focus {
                    color: $blue-light;
                }
            }
        }

        tr.details {
            td {
                border-top: 0;

                div.buttons {
                    > div:not(:first-child) {
                        margin-top: 2px;
                    }

                    .btn {
                        width: 100%;
                    }
                }
            }
        }
    }

    @media (max-width: $break-md) {
        .top-options {
            padding-right: 0 !important;
            padding-left: 0 !important;

            > div:first-child {
                margin-bottom: 5px;

                > *:not(:nth-child(2)) {
                    float: right;
                    margin-right: 0 !important;
                    margin-left: 5px;
                }

                > *:nth-child(2) {
                    float: left;
                }
            }
        }

        td:first-child {
            > i {
                zoom: 1.5;
                float: left;
                vertical-align: middle;

                &:not(:first-child) {
                    margin-left: 5px;
                }

                &.fa-caret-down, &.fa-caret-right {
                    width: 10px;
                }
            }
        }
    }
</style>

<script>
    import {mapState, mapGetters, mapActions} from 'vuex';

    export default {
        props: {

        },

        data() {
            return {
                errors: [],
                messages: [],
                expanded: [],
                stored: { users: [], roles: [] },
                new_user: { name: '', email: '', password: '', password_confirmation: '', errors: [] },
                change_pw: { user: null, password: '', password_confirmation: '', errors: [] },
                add_role: { role: '', errors: [] }
            }
        },

        computed: {
            ...mapState({ data: 'admin' }),
            ...mapGetters(['filtered_users','selected_users'])
        },

        methods: {
            ...mapActions(['updateUsers','updateRoles','toggleSelected','toggleUserRole']),

            getData (success=null) {
                axios.get('/admin/users')
                    .then(response => {
                        if (response.data) {
                            this.updateUsers(response.data.users)
                            this.updateRoles(response.data.roles)
                            if (typeof success === 'function') success()
                        }
                    })
                    .catch(error => {
                        for (var field in error.response.data) {
                            this.errors = this.errors.concat(error.response.data[field])
                        }
                    })
            },

            storeCurrentData () {
                this.stored.users = JSON.parse(JSON.stringify(this.data.users));
                this.stored.roles = JSON.parse(JSON.stringify(this.data.roles));
            },

            userUpdated (user_id) {
                var updated = false

                var stored = JSON.parse(JSON.stringify(this.stored.users.filter(user => user.id == user_id)))
                stored = (stored.length == 1) ? stored[0] : null

                var current = JSON.parse(JSON.stringify(this.data.users.filter(user => user.id == user_id)))
                current = (current.length == 1) ? current[0] : null

                if (stored && current) {
                    var stored_roles = stored.roles
                        .map(role => { return role.id })
                        .filter((role,index,self) => self.indexOf(role) === index)
                        .sort()
                    var current_roles = current.roles
                        .map(role => { return role.id })
                        .filter((role,index,self) => self.indexOf(role) === index)
                        .sort()

                    if (JSON.stringify(stored_roles) !== JSON.stringify(current_roles)) updated = true
                }

                return updated
            },

            closeModals () {
                $(this.$refs.new_user_modal).modal('hide')
                $(this.$refs.change_pw_modal).modal('hide')
                $(this.$refs.add_role_modal).modal('hide')
            },

            toggleDetails (user) {
                for (var i=0; i < this.expanded.length; i++) {
                    if (this.expanded[i] == user.id) {
                        this.expanded.splice(i,1)
                        return
                    }
                }

                this.expanded.push(user.id)
            },

            showChangePwModal (user_id=null) {
                this.change_pw.errors = []
                this.change_pw.user = user_id
                $(this.$refs.change_pw_modal).modal('show')
            },

            changePassword () {
                this.change_pw.errors = []

                var params = JSON.parse(JSON.stringify(this.change_pw))
                delete params.errors
                params.users = (this.change_pw.user) ? [this.change_pw.user] : this.selected_users.map(user => { return user.id })

                axios.put('/admin/users/password', params)
                    .then(response => {
                        this.messages = ['Password(s) successfully updated']
                        this.closeModals()
                    })
                    .catch(error => {
                        for (var field in error.response.data) {
                            this.change_pw.errors = this.change_pw.errors.concat(error.response.data[field])
                        }
                    })
            },

            saveUserRoles (user_id) {
                this.errors = []

                var params = {
                    users: this.data.users.filter(user => user.updated && user.id == user_id).map(user => { return {
                        id: user.id,
                        roles: user.roles.map(role => { return role.id })
                    }})
                }

                axios.put('/admin/users/roles', params)
                    .then(response => {
                        params.users.forEach(user => {
                            this.stored.users.filter(u => u.id == user.id).forEach(u => {
                                u.roles = this.data.roles.filter(r => user.roles.includes(r.id))
                            })
                        })
                    })
                    .catch(error => {
                        for (var field in error.response.data) {
                            this.errors = this.errors.concat(error.response.data[field])
                        }
                    })
            },

            createNewUser () {
                this.new_user.errors = []

                var params = JSON.parse(JSON.stringify(this.new_user))
                delete params.errors

                axios.put('/admin/users', params)
                    .then(response => {
                        this.getData(this.storeCurrentData)
                        this.closeModals()
                    })
                    .catch(error => {
                        for (var field in error.response.data) {
                            this.new_user.errors = this.new_user.errors.concat(error.response.data[field])
                        }
                    })
            },

            deleteUsers () {
                this.errors = []

                axios.delete('/admin/users', { params: { users: this.selected_users.map(user => { return user.id }) } })
                    .then(response => {
                        this.getData()
                        this.closeModals()
                    })
                    .catch(error => {
                        for (var field in error.response.data) {
                            this.errors = this.errors.concat(error.response.data[field])
                        }
                    })
            },

            addRole () {
                axios.put('/admin/roles', { role: this.add_role.role })
                    .then(response => {
                        this.updateRoles(response.data.roles)
                        this.closeModals()
                    })
                    .catch(error => {
                        for (var field in error.response.data) {
                            this.add_role.errors = this.change_pw.errors.concat(error.response.data[field])
                        }
                    })
            }
        },

        mounted() {
            this.getData(this.storeCurrentData)
        }
    }
</script>

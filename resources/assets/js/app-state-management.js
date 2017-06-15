import Vuex from 'vuex';

export default new Vuex.Store({
    state: {
        auth: { user: null },
        admin: { users: [], roles: [], filter: '' }
    },

    actions: {
        updateAuthUser ({commit}, auth_user) {
            commit('UPDATE_AUTH_USER', auth_user)
        },
        updateUsers ({commit}, users) {
            commit('UPDATE_USERS', users)
        },
        updateRoles ({commit}, roles) {
            commit('UPDATE_ROLES', roles)
        },
        toggleSelected ({commit}, item) {
            if (typeof item === 'undefined') return

            commit('TOGGLE_SELECTED', item)
        },
        toggleUserRole ({commit}, params) {
            if (typeof params.user === 'undefined' || typeof params.role === 'undefined') return
            if (typeof params.user === 'object') params.user = params.user.id
            if (typeof params.role === 'object') params.role = params.role.id
            if (typeof params.user === 'undefined' || typeof params.role === 'undefined') return

            commit('TOGGLE_USER_ROLE', params)
        },
        updateItemKey ({commit}, params) {
            if (typeof params !== 'object' || !params.item || !params.key) return

            commit('UPDATE_ITEM_KEY', params)
        }
    },

    getters: {
        selected_users: state => {
            return state.admin.users.filter(user => user.selected)
        },

        filtered_users: state => {
            return state.admin.users.filter( user => [user.name,user.email].join(' ').toLowerCase().includes(state.admin.filter.toLowerCase()) )
        }
    },

    mutations: {
        UPDATE_AUTH_USER (state, auth_user) {
            state.auth.user = auth_user
        },
        UPDATE_USERS (state, users=[]) {
            state.admin.users = users
        },
        UPDATE_ROLES (state, roles=[]) {
            state.admin.roles = roles
        },
        TOGGLE_SELECTED (state, item) {
            Vue.set(item, 'selected', !item.selected)
        },
        TOGGLE_USER_ROLE (state, params) {
            var user = state.admin.users.filter(user => user.id == params.user)
            user = (user.length == 1) ? user[0] : {}

            if (!Array.isArray(user.roles)) return;

            // If the user has the role, remove it
            for (var i=0; i  < user.roles.length; i++) {
                if (user.roles[i].id == params.role) {
                    user.roles.splice(i,1)
                    Vue.set(user, 'updated', true)
                    return
                }
            }

            // If the role was not found, add it
            var roles = state.admin.roles.filter(role => role.id == params.role)
            if (roles.length == 1) {
                user.roles.push(roles[0])
                Vue.set(user, 'updated', true)
            }
        },
        UPDATE_ITEM_KEY (state, params) {
            Vue.set(params.item,params.key,params.value)
        }
    }
})
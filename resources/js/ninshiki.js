import Emitter from 'tiny-emitter'
import {ZiggyVue} from "ziggy-js";
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";
import {createApp, h} from "vue";
import {createInertiaApp, router} from "@inertiajs/vue3";
import {setupAxios} from "@util/axios.js";
import {ToastEventBus} from "primevue";
import Tooltip from 'primevue/tooltip';
import {NinshikiEcho} from "@util/echo.js";
import Mousetrap from 'mousetrap'

const emitter = new Emitter()

export default class Ninshiki {
    constructor(config) {
        /** @readonly */
        this.appConfig = config
        this.toast = ToastEventBus
        this.version = config.version;
        this.appName = config.appName;
        this.$router = router
        this.websocket = config.websocket;
    }

    async engineStart() {
        this.log('Initiating Ninshiki engine...')

        const appName = this.config('appName')

        await createInertiaApp({
            title: title => (!title ? appName : `${title} - ${appName}`),
            progress: {
                delay: 250,
                color: '#29d',
                includeCSS: true,
                showSpinner: false,
            },
            resolve: name => {
                const pages = import.meta.glob('./Inertia/**/*.vue', {eager: true})
                return pages[`./Inertia/Pages/${name}.vue`]
            },
            setup: ({el, App, props, plugin}) => {
                /** @protected */
                this.mountTo = el
                /**
                 * @protected
                 * @type VueApp
                 */
                this.app = createApp({render: () => h(App, props)})

                this.app.use(ZiggyVue)
                this.app.use(PrimeVue, {
                    theme: {
                        preset: Aura,
                        options: {
                            cssLayer: {
                                name: 'primevue',
                                order: 'tailwind-base, primevue, tailwind-utilities'
                            }
                        }
                    }
                })

                this.app.directive('tooltip', Tooltip)

                this.app.use(ToastService)
                this.app.use(ConfirmationService)
                this.app.use(plugin)

                this.app.config.globalProperties.$ninshiki = this
                this.app.config.globalProperties.$echo = NinshikiEcho(this.websocket)

            },
        })
    }

    fly() {
        this.log('We have lift off!')
        this.app.mount(this.mountTo)
        this.log('All systems go...')
    }

    uiAvatar(name) {
        return `https://ui-avatars.com/api/?name=${name}&rounded=true&color=FFFFFF&background=0D8ABC`
    }

    /**
     * Log a message to the console with the NINSHIKI prefix
     *
     * @param {string} message
     * @param {string} [type=log]
     */
    log(message, type = 'log') {
        console[type](`[NINSHIKI]`, message)
    }

    /**
     * Log a message to the console for debugging purpose
     *
     * @param {any} message
     * @param {string} [type=log]
     */
    debug(message, type = 'log') {
        const debugEnabled =
            process.env.NODE_ENV === true || (this.config('debug') ?? false)

        if (debugEnabled === true) {
            if (type === 'error') {
                console.error(message)
            } else {
                this.log(message, type)
            }
        }
    }

    /**
     * Return configuration value from a key.
     *
     * @param  {string} key
     * @returns {any}
     */
    config(key) {
        return this.appConfig[key]
    }


    /**
     * Register a listener on Ninshiki's built-in event bus
     *
     * @param args
     */
    $on(...args) {
        emitter.on(...args)
    }

    /**
     * Register a one-time listener on the event bus
     *
     * @param args
     */
    $once(...args) {
        emitter.once(...args)
    }

    /**
     * Unregister an listener on the event bus
     *
     * @param args
     */
    $off(...args) {
        emitter.off(...args)
    }

    /**
     * Emit an event on the event bus
     *
     * @param args
     */
    $emit(...args) {
        emitter.emit(...args)
    }

    /**
     * Return an axios instance configured to make requests to Ninshiki's API
     * and handle certain response codes.
     *
     * @param {AxiosRequestConfig|null} [options=null]
     * @returns {AxiosInstance}
     */
    request(options = null) {
        /** @type AxiosInstance */
        let axios = setupAxios()

        if (options != null) {
            return axios(options)
        }

        return axios
    }


    /**
     * Return an Undefined or Echo instance
     *
     * @returns {undefined|NinshikiEcho}
     */
    $echo() {
        if (!this.websocket.enabled) return undefined;
        return NinshikiEcho(this.websocket)
    }

    /**
     * Register a keyboard shortcut.
     *
     * @param {string} keys
     * @param {Function} callback
     */
    addShortcut(keys, callback) {
        Mousetrap.bind(keys, callback)
    }

    /**
     * Unbind a keyboard shortcut.
     *
     * @param {string} keys
     */
    disableShortcut(keys) {
        Mousetrap.unbind(keys)
    }

    /**
     * Show an error message to the user.
     *
     * @param {string} message
     * @param {string} summary
     */
    info(message, summary) {
        this.toast.emit('add', {
            severity: 'info',
            summary: summary,
            detail: message,
            group: 'br',
            life: 5000
        })

    }

    /**
     * Show an error message to the user.
     *
     * @param {string} message
     * @param  {string} summary
     */
    error(message, summary) {
        this.toast.emit('add', {
            severity: 'error',
            summary: summary,
            detail: message,
            group: 'br',
            life: 5000
        })
    }

    /**
     * Show a success message to the user.
     *
     * @param {string} message
     * @param summary
     */
    success(message, summary) {
        this.toast.emit('add', {
            severity: 'success',
            summary: summary,
            detail: message,
            group: 'br',
            life: 5000
        })
    }

    /**
     * Show a warning message to the user.
     *
     * @param {string} message
     * @param {string} summary
     */
    warning(message, summary) {
        this.toast.emit('add', {
            severity: 'warn',
            summary: summary,
            detail: message,
            group: 'br',
            life: 5000
        })
    }
}



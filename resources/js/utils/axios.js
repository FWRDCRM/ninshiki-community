import axios from 'axios';

/**
 * @returns {import('axios').AxiosInstance}
 */
export function setupAxios() {
    const instance = axios.create();

    instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    instance.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

    instance.interceptors.response.use(
        (response) => response,
        (error) => {
            if (axios.isCancel(error)) {
                return Promise.reject(error);
            }

            const response = error.response;
            const {
                status,
                data: { redirect },
            } = response;

            // Show the user a 500 error
            if (status >= 500) {
                NinshikiApp.$emit('error', error.response.data.message);
            }

            // Handle Session Timeouts (Unauthorized)
            if (status === 401) {
                // Use redirect if being specificed by the response
                if (redirect != null) {
                    location.href = redirect;
                    return;
                }
                NinshikiApp.$router.visit(route('login.page'));
            }

            // Handle Forbidden
            if (status === 403) {
                location.href = '/403';
            }

            // Handle Token Timeouts
            if (status === 419) {
                NinshikiApp.$emit('token-expired');
            }

            return Promise.reject(error);
        },
    );

    return instance;
}

// un set pour éviter la requête répétée
axios.interceptors.request.use(function (config) {
  console.log(config.data)
    const token = localStorage.getItem('token');
    if (token) {
    config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

axios.interceptors.response.use(function (response) {


    const token = response.headers.authorization;
    if (token) {
        localStorage.setItem('token', token);
    }
    return response;
    }, (error) => {

    const { response } = error;
    if (response && (response.status === 401 || response.status === 403)) {
        localStorage.removeItem('token')
        window.location.href="../index.html"
    }
    return Promise.reject(error);
});
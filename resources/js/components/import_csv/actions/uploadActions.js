import axios from "axios";

export const MODULE_KEY = "module_upload_file";
export const SET_UPLOADING = MODULE_KEY + 'set_uploading';
export const ON_SAVE_FILE = MODULE_KEY + 'on_save_file';
export const ON_SAVE_FILE_ERROR = MODULE_KEY + 'on_save_file_error';
export const ON_RESET_FILE = MODULE_KEY + 'on_reset_file';


export const upload_file = (file) => (dispatch) => {
    let formData = new FormData();
    formData.append('file', file);

    dispatch({type: SET_UPLOADING, payload: true});
    const config = { headers: { 'Content-Type': 'multipart/form-data' } };
    axios.post('/utils/upload_file', formData, config)
        .then((response) => {
        const data = response.data;
        if (data.status === 1){
            dispatch({
                type: ON_SAVE_FILE,
                payload: {value : data.file, msg : data.msg}
            });
        }else if (data.status === 0){
            dispatch({
                type: ON_SAVE_FILE_ERROR,
                payload: {msg : 'Error : ' + data.msg}
            });
        }
    });
};


export const reset_file = () => (dispatch) => {
    dispatch({
        type: ON_RESET_FILE,
        payload: {}
    });
};

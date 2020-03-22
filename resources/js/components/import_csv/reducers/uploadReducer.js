import {ON_RESET_FILE, ON_SAVE_FILE, ON_SAVE_FILE_ERROR, SET_UPLOADING} from "../actions/uploadActions";

const initialState = {
    isUploading: false,
    file : undefined,
    error : false,
    msg : '',
};


export default function (state = initialState, action) {
    switch (action.type) {
        case SET_UPLOADING :
            return {
                ...state,
                isUploading : action.payload
            };
        case ON_SAVE_FILE:
            return {
                ...state,
                file: action.payload.value,
                error : false,
                msg : action.payload.msg,
                isUploading : false,
            };
        case ON_SAVE_FILE_ERROR:
            return {
                ...state,
                error : true,
                msg : action.payload.msg,
                file: undefined,
                isUploading : false,
            };
        case ON_RESET_FILE:
            return {
                ...state,
                error : false,
                msg : '',
                file: undefined,
                isUploading : false,
            };

        default : return state;
    }
}

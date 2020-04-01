import {ON_FETCH_FNO_SYMBOLS, ON_FILTER, SET_LOADING_ON_LIKE} from "../actions/types";

const initialState = {
    is_loading: false,
    queryStr : '',
    fno_symbols : [],
    filteredSymbols : [],
    idx : 0,
    loading : false,
};


export default function (state = initialState, action) {
    switch (action.type) {
        case ON_FILTER:
            return {
                ...state,
                queryStr : action.payload.value,
                filteredSymbols : action.payload.filteredSymbols,
            };

        case ON_FETCH_FNO_SYMBOLS:
            return {
                ...state,
                fno_symbols : action.payload,
                filteredSymbols : action.payload,
            };

        case SET_LOADING_ON_LIKE:
            return {
                ...state,
                idx : action.payload.idx,
                loading : action.payload.loading,
            };
        default : return state;
    }
}

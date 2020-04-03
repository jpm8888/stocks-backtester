import {
    LIST_FNO,
    ON_FAV_DONE,
    ON_FETCH_FNO_SYMBOLS,
    ON_FILTER,
    ON_SELECT_LIST,
    SET_LOADING_ON_LIKE
} from "../actions/types";

const initialState = {
    is_loading: false,
    queryStr : '',
    fno_symbols : [],
    filteredSymbols : [],
    idx : 0,
    loading : false,


    selectedList : LIST_FNO, //LIST_FNO, LIST_FAVS
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

        case ON_FAV_DONE:
            return {
                ...state,
                fno_symbols : action.payload.symbols,
                filteredSymbols : action.payload.filtered,
            };

        case SET_LOADING_ON_LIKE:
            return {
                ...state,
                idx : action.payload.idx,
                loading : action.payload.loading,
            };

        case ON_SELECT_LIST:
            return {
                ...state,
                selectedList : action.payload,
                queryStr : ''
            };

        default : return state;
    }
}

import {FETCH_PARAMS} from "../actions/types";

const initialState = {
    is_loading : false,
    banknifty : []
};


export default function (state = initialState, action) {
    switch (action.type) {
        case FETCH_PARAMS:
            return{
                ...state,
                banknifty: action.payload.banknifty
            };
        default : return state;
    }
}

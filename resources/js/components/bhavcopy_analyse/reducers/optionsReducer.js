import {FETCH_PARAMS} from "../actions/types";

const initialState = {
    expiry_dates : [],
};


export default function (state = initialState, action) {
    switch (action.type) {
        case FETCH_PARAMS:
            return{
                ...state,
                expiry_dates: action.payload.expiry_dates
            };
        default : return state;
    }
}

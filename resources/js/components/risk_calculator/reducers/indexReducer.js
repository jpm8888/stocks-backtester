import {ON_CHANGE} from "../actions/types";

const initialState = {
    capital_amount : 0,
    entry : 0,
    stop_loss : 0,
};


export default function (state = initialState, action) {
    switch (action.type) {
        case ON_CHANGE:
            return{
                ...state,
                [action.payload.name] : action.payload.value
            };
        default : return state;
    }
}

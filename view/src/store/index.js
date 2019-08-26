import React, { useReducer } from "react";

const INITIAL_STATE = {
    xlsx: [],
    toVerify: [],
    header: [],
    enel_header: [],
    isLoaded: false,
    qualityRated: 0,
    status: 0,
    finds: [],
    notFind: []
};

const Types = {
    loaded: "loaded",
    init: "inicialize",
    setH: "set_header",
    setHE: "set_header_enel",
    setVerify: "set_to_verify",
    updateStatus: "update_status",
    updateQRated: "update_quality_rated",
    finds: "set_find_index",
    notFinds: "set_find_not_index"
};
export const functionsToDispatch = {
    loaded: () => ({ type: Types.loaded }),
    init: payload => ({ type: Types.init, payload }),
    set_header: payload => ({ type: Types.setH, payload }),
    set_header_enel: payload => ({ type: Types.setHE, payload }),
    set_to_verify: payload => ({ type: Types.setVerify, payload }),
    update_status: payload => ({ type: Types.updateStatus, payload }),
    update_quality_rated: payload => ({ type: Types.updateQRated, payload }),
    set_find_index: payload => ({ type: Types.finds, payload }),
    set_find_not_index: payload => ({ type: Types.notFinds, payload })
};
export const Context = React.createContext(INITIAL_STATE);
function reducer(state = INITIAL_STATE, action) {
    switch (action.type) {
        case Types.finds:
            return {
                ...state, finds: action.payload.map(({ index }, ) => index)
                /*
                finds: action.payload.reduce((arr, v) => {
                    if (v.find) {
                        arr = [...arr, v]
                    }
                    return arr
                }, [])*/
            };
        case Types.notFinds:
            return {
                ...state, notFind: action.payload.map(({ index }, i) => index)
                /*
                finds: action.payload.reduce((arr, v) => {
                    if (v.find) {
                        arr = [...arr, v]
                    }
                    return arr
                }, [])*/
            };
        case Types.setHE:
            return {
                ...state,
                enel_header: action.payload
            };
        case Types.init:
            return {
                ...INITIAL_STATE,
                xlsx: [...action.payload]
            };
        case Types.setH:
            return { ...state, header: [...action.payload] };
        case Types.setVerify:
            return {
                ...state,
                toVerify: action.payload
            };
        case Types.loaded:
            return { ...state, isLoaded: !state.isLoaded };
        case Types.updateStatus:
            return {
                ...state,
                status: action.payload
            };
        case Types.updateQRated:
            return { ...state, qualityRated: action.payload };
        default:
            return state;
    }
}
export default function Store({ children }) {
    const [store, dispatch] = useReducer(reducer, INITIAL_STATE);
    return (
        <Context.Provider value={[store, dispatch]}>{children}</Context.Provider>
    );
}

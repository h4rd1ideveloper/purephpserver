import React, { useReducer } from 'react';
const INITIAL_STATE = { xlsx: [], toVerify: [], header: [], isLoaded: false, qualityRated: 0 }
const Types = {
    loaded: 'loaded',
    init: 'inicialize',
    setH: 'set_header',
    setVerify: 'set_to_verify',
    updateStatus: 'update_code_verifica',
    updateQRated: 'update_quality_rated'
}
export const functionsToDispatch = {
    loaded: () => ({ type: Types.loaded }),
    init: payload => ({ type: Types.init, payload }),
    set_header: payload => ({ type: Types.setH, payload }),
    set_to_verify: () => ({ type: Types.setVerify }),
    update_code_verifica: payload => ({ type: Types.updateStatus, payload }),
    update_quality_rated: payload => ({ type: Types.updateQRated, payload })
}
export const Context = React.createContext(INITIAL_STATE)
function reducer(state = INITIAL_STATE, action) {
    switch (action.type) {
        case Types.init:
            return {
                ...state, xlsx: [...action.payload]
            }
        case Types.setH:
            return { ...state, header: [...action.payload] }
        case Types.setVerify:
            return {
                ...state,
                toVerify: [
                    ...state.xlsx.map((row) => {
                        return [
                            ...["Numero_Cliente", "dv", "Data Inclusao", "Empresa", "Produto", "Referencia", "Data Baixa", "Correlativo", "Tipo Doc", "numero_emp_parc"].map((titulo) => {
                                if(titulo ==="Referencia"){
                                  return `${row[`${titulo}`].split("/")[1]}/${row[`${titulo}`].split("/")[0]}`;
                                }
                                return row[`${titulo}`];
                            })
                        ]
                    })
                ]
            }
        case Types.loaded:
            return { ...state, isLoaded: !state.isLoaded }
        case Types.updateStatus:
            return {
                ...state, xlsx: state.xlsx.map((linha, index) => {
                    return (action.payload.index !== index) ? linha : linha.map((coluna, i) => {
                        return ((linha.length - 1) !== i) ? coluna : action.status;
                    })
                })
            }
        case Types.updateQRated:
            return { ...state, qualityRated: action.payload }
        default:
            return state
    }
}
export default function Store({ children }) {
    const [store, dispatch] = useReducer(reducer, INITIAL_STATE)
    return (
        <Context.Provider value={[store, dispatch]}>{children}</Context.Provider>
    )
}
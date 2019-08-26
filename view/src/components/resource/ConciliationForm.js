import React, { useState, useContext, useEffect } from "react";
import { functionsToDispatch, Context } from "../../store";
import {
    formattedFielsAndvalues,
    updateProgress
} from "../../lib/helpers";
import axios from "axios";
const {
    set_to_verify,
    update_status,
    set_find_not_index,
    set_find_index
} = functionsToDispatch;

export default function Resource({ labels_xlsx, labels_enel, xlsx }) {
    const [, setContext] = useContext(Context);
    const [state, setState] = useState({});

    function updateObjFields(e) {
        e.preventDefault();
        setState({ ...state, [`${e.target.name}`]: `${e.target.value}` });
    }
    function sendObjtFields(e) {
        e.preventDefault();
        const toVerify = formattedFielsAndvalues(xlsx, state);
        setContext(set_to_verify(toVerify));

        const myAsyncIterable = {};
        myAsyncIterable[Symbol.asyncIterator] = async function* () {
            let index = 0;
            for (const k of toVerify) {
                let toCheck = Object.values(k).reduce((obj, r) => {
                    obj = { ...obj, ...r };
                    return obj;
                }, {});
                setContext(update_status(updateProgress(index + 1, toVerify.length)));
                index++;
                yield (await (await axios({
                    url: "http://localhost:8080/check",
                    method: "get",
                    params: toCheck
                })).data) === 1
                    ? { find: true, index: index - 1 }
                    : { find: false, index: index - 1 };
            }
        };
        let response = [];
        (async () => {
            for await (const x of myAsyncIterable) {
                console.log(x);
                response = [...response, x];
            }
            console.log(response);
            setContext(set_find_not_index(response));
            setContext(set_find_index(response));
        })();
    }
    let i;
    const component = labels_enel.map((value, index) => {
        i = index;
        return (
            <div key={index} className="col-12 col-sm-6 col-md-4 col-lg-2 mx-md-auto">
                <label htmlFor={value}>{value}</label>
                <select
                    onChange={updateObjFields}
                    className="form-control"
                    name={value}
                    id={`id-${value}`}
                >
                    <option selected disabled hidden>
                        Não utilizar
          </option>
                    {labels_xlsx.map(function (value, index) {
                        return (
                            <option key={index} value={value}>
                                {value}
                            </option>
                        );
                    })}
                </select>
            </div>
        );
    });
    return (
        <div className="row">
            {component}
            <button key={Number(i + 1)} onClick={sendObjtFields} className="mx-auto btn btn-lg btn-block btn-outline-dark my-5" children={"Conciliar"} />

        </div>
    );
}

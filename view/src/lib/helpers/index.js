// eslint-disable-next-line no-unused-vars
import React, { useMemo } from 'react';
export function Memorize({ fact = () => (<div>Miss JSXElement</div>), deps = [] }) {
    return useMemo(fact, deps);
}
export function updateProgress(now = 0, total = 1) {
    return Number(Math.floor((now * 100) / total));
}
export function someDate(obj = {}) {
    return obj.hasOwnProperty('dataBaixaPagamento') && obj['dataBaixaPagamento'] || obj.hasOwnProperty('dataOcorrencia') && obj['dataOcorrencia'] || obj.hasOwnProperty('dataPagamento') && obj['dataPagamento']
}
export function formattedFielsAndvalues(xlsx = [], state = {}) {
    return xlsx.map(function (row, i) {
        return {
            ...Object.entries(state).map(
                function (label, i) {
                    return { [`${label[0]}`]: label[0] === 'valor' ? `${row[label[1]]}`.replace(/(,|\.)/g, 'k') : row[label[1]] };
                }
            )
        }
    })
}
export function verifyEmptyContent(row) {
    let feedback = []
    let ok = true;
    for (const key in row) {
        if (row[key] === "") {
            feedback = [...feedback, key]
            ok = false;
        }
    }
    return { lines: feedback, ok };
}
export function countValidContent(storaged = [], header = []) {
    let acc = 0;
    storaged.map((row, index) => {
        header.map((titulo) => {
            if (row[`${titulo}`] !== "") {
                acc++;
            }
            return titulo;
        })
        return storaged;
    })
    return acc;
}

const rows = "`COD`, `numeroCliente`, `dvNumeroCliente`, `codOcorr`, `dataOcorrencia`, `valor`, `numParcelas`, `referencia`, `dataVencimento`, `dataPagamento`, `loteFaturamento`, `numeroClienteEmpPar`, `codProduto`, `empresaParc`, `dataBaixaPagamento`, `codCanalVenda`, `tipoRetorno`, `descricaoRetorno`, `correlativoDocumento`, `tipoDocumento`, `data`, `controle`, `ENEL_COD_IMPORT`";

export { rows };
//como usar
/**
 *
 * const myAsyncIterable = new Object();
        myAsyncIterable[Symbol.asyncIterator] = async function* () {
            //Processar cada linha do XLSX e retorna pelo yield cada resposta das requisições

            for (const x of rootContext) {

                   yield await (
                            await (
                                axios.post('http://localhost:8080/insert',
                                    {
                                        table: "xlsx_arrecadacao",
                                        values: [`${k[0]}`, `${k[1]}`],
                                        fields
                                    }
                                )
                            )
                        ).data;
            }
        };

        (async () => {
            for await (const x of myAsyncIterable) {
                console.log(x);
                // expected output:
                //    "hello"
                //    "async"
                //    "iteration!"
            }
        })();
 */
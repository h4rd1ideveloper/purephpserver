import { Memorize } from '../../lib/helpers';
import React, { useRef, useState, useContext } from 'react';
import { Context, functionsToDispatch } from '../../store'
import * as XLSX from 'xlsx';
import { FeedBack } from '../../lib/feedback'

export default function () {
    const { init, set_header, loaded } = functionsToDispatch;
    let inputRef = useRef(null);
    const [progress,] = useState(0)
    const [, setContext] = useContext(Context);
    function handleFile(e) {
        var files = e.target.files, f = files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, { type: 'array', cellDates: true, cellNF: false, cellText: false });
            let storage = [];
            for (const name of workbook.SheetNames) {
                storage = [
                    ...storage,
                    ...XLSX.utils
                        .sheet_to_json(
                            workbook.Sheets[`${name}`], { dateNF: "YYYY/mm/dd", raw: false }
                        )
                ]

            }
            setContext(init(storage))
            let keysXLSX = Object.keys(storage[0]);
            setContext(set_header(keysXLSX))
            setContext(loaded())
            FeedBack.fire({
                type: 'success',
                title: 'ok...',
                text: ` A planilha foi lida `,
                footer: `<b>${workbook.SheetNames.toString()}</b>`,
                timer: 1500,
                width: 300,
            })
            //setContext(set_to_verify())
        };
        reader.readAsArrayBuffer(f);
    }
    return Memorize({
        fact: () => (<>
            <div className="form-group my-5">
                <div className="form-group">
                    <label className='btn btn-dark m-0' htmlFor='fileToUpload'>Selecionar um arquivo &#187;</label>
                    <input
                        style={{
                            display: 'none'
                        }}
                        ref={inputRef}
                        required
                        className="form-control-file btn btn-secondary"
                        type='file'
                        name='fileToUpload'
                        id='fileToUpload'
                        onChange={handleFile}
                    />
                </div>
            </div>
        </>),
        deps: [progress]
    });
}
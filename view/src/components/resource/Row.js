import React, { useRef, useState } from 'react';
export default function ({ children, classes, lockable }) {
    const toCloseRow = useRef(null);
    const [{ isOpen }, setState] = useState({ isOpen: true })

    function hide(e) {
        e.preventDefault()
        console.log(toCloseRow.current)
        setState({ isOpen: !isOpen })
    }
    return (
        <>
            <div ref={toCloseRow} className={`row ${!isOpen && 'd-none'} ${classes || ''}`}>{children} </div>
            <div className="row">
                <a className="col-12 w-100 ml-auto text-muted text-right" onClick={hide}>{isOpen && 'esconder'||'mostrar'}</a>
            </div>
        </>
    )
}
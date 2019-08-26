import React from 'react';
export default function ({ classes, children }) {
    return (
        <div className={`${classes||'col-12'}`}>{children}</div>
    );
}
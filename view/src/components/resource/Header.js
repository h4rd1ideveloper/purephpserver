import React from 'react';
import { Memorize } from '../../lib/helpers/index';
export default function ({ children }) {
    return Memorize({
        fact: () => (
            <>
                <nav className="navbar navbar-dark bg-dark">
                    <a className="navbar-brand" href="/">Valida planilha</a>
                    <>
                        {children}
                    </>
                </nav>
            </>
        ),
        deps: []
    })
}
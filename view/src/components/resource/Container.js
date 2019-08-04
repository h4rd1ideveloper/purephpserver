import React from 'react';
import {Memorize} from '../../lib/helpers/index';
export default function({children, ...props}){
    return Memorize(
        {
            fact: () => <div className="container">{children}</div>,
            deps: []
        }
    );
}
import React from 'react';
import {Memorize} from '../../lib/helpers/index';
export default function({classes, children}){

    return(
        Memorize({ fact:()=>(<div className={`${classes}`}>{children}</div>), deps: [] })
    );
}
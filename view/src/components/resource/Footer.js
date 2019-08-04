import React from 'react';
import {Memorize} from '../../lib/helpers/index';
export default function({children}){
    return(
        Memorize({ fact:()=>(<form className="footer">{children}</form>), deps: [] })
    );
}
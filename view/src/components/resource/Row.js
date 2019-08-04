import React from 'react';
import {Memorize} from '../../lib/helpers/index';

export default function ({ children, classes }) {
    return Memorize({
        fact: () =>  <div className={`row ${classes}`}>{children}</div>,
        deps: []
    })
}
import React, { useContext, useEffect } from 'react';
import { Memorize, updateProgress, countValidContent } from '../../lib/helpers';
import { Context, functionsToDispatch } from '../../store';

export default function () {
    const [{status:taxa}] = useContext(Context);
    //alias
    const status = {
        width: `${taxa}%`,
        animation: 'all 1s ease-in-out'
    }
    return Memorize({
        fact: () => {
            if(taxa){
                return (
                    <div className="progress w-100">
                        <div
                            style={status}
                            className="progress-bar"
                            role="progressbar"
                            aria-valuenow={taxa}
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >{taxa}%</div>
                    </div>
                );
            }else {
                return <></>;
            }
        }, deps: [taxa]
    });
}
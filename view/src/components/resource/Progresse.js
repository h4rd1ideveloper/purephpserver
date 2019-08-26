import React, { useContext, useEffect } from 'react';
import { Memorize, updateProgress, countValidContent } from '../../lib/helpers';
import { Context, functionsToDispatch } from '../../store';

export default function () {
    //alias
    const { update_quality_rated } = functionsToDispatch
    const [{ isLoaded, xlsx, header, qualityRated: progress }, setContext] = useContext(Context)

    useEffect(
        function () {
            setContext(
                update_quality_rated(
                    updateProgress(
                        countValidContent(xlsx, header), Math.floor(header.length * xlsx.length) || 1
                    )
                )
            )
            // eslint-disable-next-line
        }, [xlsx, header]
    )
    const status = {
        width: `${progress}%`,
        animation: 'all 1s ease-in-out'
    }
    return Memorize({
        fact: () => {
            if (isLoaded) {
                return (
                    <div className="progress w-100">
                        <div
                            style={status}
                            className="progress-bar"
                            role="progressbar"
                            aria-valuenow={progress}
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >{progress}%</div>
                    </div>
                );
            } else {
                return <></>;
            }

        }, deps: [progress, xlsx, header, isLoaded]
    });
}
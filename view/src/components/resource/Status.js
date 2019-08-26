import React, { useContext, useEffect } from 'react';
import { Memorize, updateProgress } from '../../lib/helpers';
import { Context } from '../../store';
export default function () {
    const [{ xlsx, header, qualityRated: progress }, ] = useContext(Context)
    const status = {
        width: `${progress}%`,
        animation: 'all 1s ease-in-out'
    }
    return Memorize({
        fact: () => (
            <div className="progress">
                <div
                    style={status}
                    className="progress-bar"
                    role="progressbar"
                    aria-valuenow={progress}
                    aria-valuemin="0"
                    aria-valuemax="100"
                >{progress}%</div>
            </div>
        ), deps: [progress, xlsx, header]
    });
}
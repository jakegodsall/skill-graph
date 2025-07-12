import { useCallback } from "react";

export default function CourseNode() {
    const onChange = useCallback((evt) => {
        console.log(evt.target.value);
    }, []);
    
    return (
        <div>
            <div>
                <p className="text-red">COURSE NODE</p>
            </div>
        </div>
    );
}
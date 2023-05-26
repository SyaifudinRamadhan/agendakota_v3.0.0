const presets = () => {
    return (
        [[[
            // Preset single
            {
                x:0,
                y:0,
                width:1920,
                height:1080,
                mute: true
            }
        ]],[[
            // preset double
            // video 1
            {
                x:0,
                y:270,
                width:960,
                height:540,
                mute: true
            },
            // video 2
            {
                x:961,
                y:270,
                width:960,
                height:540,
                mute: true
            }
        ]],[[
            // preset kuadran
            // video 1
            {
                x:0,
                y:0,
                width:960,
                height:540,
                mute: true
            },
            // video 2
            {
                x:961,
                y:0,
                width:960,
                height:540,
                mute: true
            },
            // video 3
            {
                x:0,
                y:541,
                width:960,
                height:540,
                mute: true
            },
            // video 4
            {
                x:961,
                y:541,
                width:960,
                height:540,
                mute: true
            }
        ]],[[
            // preset tutorial
            // video 1
            {
                x:0,
                y:0,
                width:1920,
                height:1080,
                mute: true
            },
            // video 2
            {
                x:1280,
                y:720,
                width:640,
                height:360,
                mute: true
            }
        ]],[[
            // preset triple
            // video 1
            {
                x:0,
                y:0,
                width:960,
                height:1080,
                mute: true
            },
            // video 2
            {
                x:961,
                y:0,
                width:960,
                height:540,
                mute: true
            },
            // video 3
            {
                x:961,
                y:541,
                width:960,
                height:540,
                mute: true
            },
        ]]]
    )
}

export default presets
export default {
    placeholder: 'ここにタスクの詳細や指示内容を入力してください。テキストの装飾が利用可能です。',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline', 'strike'],

            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'align': [] }],

            ['clean']                                         // remove formatting button
        ],
    },
    theme: 'snow',
};

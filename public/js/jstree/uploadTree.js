$(document).ready(function () {
    //var test = {{ tree | json_encode | raw }}
    $('#inputGroupFile01').change(function (data) {
        var files = data.currentTarget.files;
        console.log('files');
        console.log(files);
        var newTree = {};
        var childrenTree = {};
        var children = [];
        var array = [];
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var iter = 0;
            //console.log(file);

            var folderTree = file.webkitRelativePath.split('/');
            //console.log(folderTree);
            switch (folderTree.length) {

                case 3:
                    newTree['text'] = folderTree[0];
                    //children['text'] = folderTree[1];
                    newTree['children'] = [
                        {
                            'text': folderTree[1],
                            'children': [
                                {
                                    'text': folderTree[2]
                                }
                            ]
                        }
                    ];
                    children[files[iter]] = file[i];



            }
            children.push(newTree);
        }
        console.log(children);
    });

    $('#jstree_demo_div')
        .jstree({
            'core': {
                'data': newTree,
                'check_callback': true
            },
            'plugins': [
                'dnd',
                'contextmenu',
                'changed'
            ]
        });


})
> 20190410 一句话搞定5星评分
"★★★★★☆☆☆☆☆".substring(5 - rating, 10 - rating);

> 数组去重 https://dmitripavlutin.com/javascript-array-from-applications/
function unique(array) {
return Array.from(new Set(array));
}
unique([1, 1, 2, 3, 3]); // => [1, 2, 3]

> 合并对象
let form = {name: 'liming', sex: '男'};
let obj = {class: '一班', age: 15};
Object.assign(form, obj);

> 对象转 json
JSON.stringify(obj);

> 判断对象是否为空
1. JSON.stringify(obj) === '{}'
2. Object.keys(obj).length === 0

> 判断对象是不是数组
var arr = [1,2,3,1];
alert(arr instanceof Array); // true
alert(arr.constructor === Array); // true

> 检测数组 site 是否包含 runoob
let site = ['runoob', 'google', 'taobao'];
site.includes('runoob'); // true 

> console.log / color
console.log("%c U %c Want something shining ",'background: linear-gradient(to right, #09f0fb, #047edf);color:#fff;border-radius:2px 0 0 2px', 'background:#673ab7;border-radius:0 2px 2px 0; color:#fff;');
- 'background: linear-gradient(to right, #09f0fb, #047edf);color:#fff;border-radius:2px 0 0 2px', 'background: linear-gradient(to right, #047edf, #b66dff);border-radius:0 2px 2px 0; color:#fff;' - #673ab7

> 交互式标题
var OriginTitile = document.title, st;
document.addEventListener("visibilitychange", function () {document.hidden ? (document.title = "看不见我🙈~看不见我🙈~", clearTimeout(st)) :
(document.title = "(๑•̀ㅂ•́) ✧被发现了～", st = setTimeout(function () {document.title = OriginTitile}, 3e3))})

> 正则
str.replace(/[^0-9\;]/ig,"");
str.replace(/<[^>]+>/g,""); // 取出 html
/(?<=i\/).*?(?=\/)/ // 两个特殊符号之间的内容
var str = 'trem-1234';
var s=/\w+\-(\d+)/.exec(str);

> 获取字符串长度
function codePointLength(text) {
  var result = text.match(/[\s\S]/gu);
  return result ? result.length : 0;
}

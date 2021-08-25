> 查找并替换
%s/path_to_website/var\/www\/ocart/g
# foo => foobar
%s/\(foo\)/&bar/g
%s/\(foo\)/\1bar/g

%s/\s\+/ /g
%s/\(^\d\+\)/("1 \1/g
%s/\(\n\)/"),\1/g
%s/ /","/g

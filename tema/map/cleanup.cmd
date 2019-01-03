@echo off

rmdir /s /q x64
rmdir /s /q Release
rmdir /s /q Debug

attrib -r -s -h *.ncb
attrib -r -s -h *.suo
attrib -r -s -h *.db
del *.ncb
del *.suo
del *.db

attrib -r -s -h .vs
rmdir /s /q .vs

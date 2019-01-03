#include <Windows.h>
#include <stdio.h>

#include "map.h"

int main(int argc, char** argv)
{
	MAPPING map;
	DWORD result;

	result = MapFile(argv[1], GENERIC_READ | GENERIC_WRITE, &map);
	if (ERROR_SUCCESS != result)
	{
		printf("MapFile failed with result %u\n", result);
		return result;
	}

	printf("MapFile succeeded. First char = %c \n", map.Data[0]);
	
	//map.Data[0] = 'a';

	UnmapFile(&map);

	return ERROR_SUCCESS;
}
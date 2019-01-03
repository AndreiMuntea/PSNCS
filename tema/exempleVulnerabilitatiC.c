#include <stdio.h>
#include <string.h>
#include <windows.h>

// in acest fisier sunt mai multe exemple de scenarii imaginate care contin vulnerabilitati

#define MAX_BUFF 64
__int64 N = 0;
BOOLEAN OF = FALSE;
BOOLEAN firstTime = TRUE;

void talloc(DWORD Size) // functie care presupunem ca aloca un buffer de dimensiune size
{
    N = Size & 0x00000000FFFFFFFFull;
    printf("S-a alocat %I64u \n", N);
    OF = FALSE;
}

void write(size_t Size) // functie care presupunem ca scrie in bufferul alocat de functia talloc
{
    N = N - Size;
    printf("Am scris %I32u \n", Size);

    if (N <= 0 && !OF)
    {
        printf("Am depasit bufferul\n");
        OF = TRUE;
    }
}

void exemplu1()
{
    unsigned int h, w, n;
    unsigned int i;

    h = 0x7FFFFFFF;
    w = 2;

    n = h * w;

    printf("n = %d \n", n);

    talloc(n);

    for(i = 0; i < h; i++)
    {
        write(w);
    }
}

void exemplu2()
{
    unsigned int n;
    unsigned int i;

    n = 0x80000001;

    if (n > 0)
    {
        talloc(n * sizeof(int));
    } 

    for(i = 0; i < n; i++)
    {
        write(sizeof(int));
    }
}

void exemplu3()
{
    unsigned int n;

    n = 10;

    talloc(MAX_BUFF);

    if (n > MAX_BUFF + 20 - 1)
    {
       printf("buffer to small \n");
       return;
    } 

    write(n - 20);

}

void exemplu4()
{
    int n;

    n = 0x7FFFFFFF;

    talloc(MAX_BUFF);

    if (n < 0 || n + 1 >= MAX_BUFF)  
    {
        printf("buffer to small \n");
        return;
    } 

    write(n);  

}

void exemplu5()
{
    int n;

    n = 0x80000000;
    
    if (n < 0)  
    {
        n = -n;
    } 

    printf("Negativul lui %X este %X\n", 0x80000000, n);
}

void exemplu6()
{
    int n;

    n = -1;

    talloc(MAX_BUFF);

    if (n > MAX_BUFF)
    {
        printf("buffer to small \n");
        return;
    } 

    write(n); 
}

void exemplu7()
{
    int n;
    size_t now;
    now = 0;
    talloc(MAX_BUFF);
    n = 2;
    while(n)
    {
        if (now + n < (MAX_BUFF - 1))
        {
            write(n);
            now += n;
            n = -1;
        }
        else
        {
            printf("Depasire \n");
            n = 0;
        }
    }
    
}

void exemplu8()
{
    int n;

    n = -1;

    if (n > MAX_BUFF)  
    {
        printf("buffer to small \n");
        return;
    } 

    talloc(n + 1);

    write(n); 
}

void exemplu10()
{
    int now = 0;
    char c = -1;
    char *p = "\\aaaaabbbb\\";

    while(1)
    {
        if (c != -1 && c!='\\')  
        {
            write(1);
            now++;
            if (now > MAX_BUFF)
            {
                break;
            }
        }

        c = *p++;

        if (c=='\\')  
        {
            write(1);
            now++;
        }
    }
}

void setUser9(unsigned short uid)
{
    printf("Am setat userul la %d \n", uid);
}

void exemplu9()
{
    int uid;
      
    uid = 0x80000000;

    if (0 == uid)
    {
        printf("Nu poti seta root user\n");
        return;
    }

    setUser9(uid);
}

void loginUser11(char* user, char* parola)
{
    printf("Am setat userul la %s \n", user);
}

char *getBuff()
{
    if (firstTime)
    {
        firstTime = FALSE;
        return "user";
    }
    else
        return "password";
}

void exemplu11()
{
    loginUser11(getBuff(), getBuff());
}

int main(int argc, char* argv[])
{
	// aici se pot apela toate exemplele pe rand
    //exemplu1();

    return 0;
}
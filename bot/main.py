import asyncio
import discord
import pymysql
import time
from discord.ext import commands

bot = commands.Bot('#', description='TestWork')
host = '<host_mysql>'
user = '<login_mysql>'
password = '<password_mysql>'
db = '<database_mysql>'
charset = 'utf8'
token = '<token>'


@bot.event
async def on_ready():
    print('Logged in as:\n{0.user.name}\n{0.user.id}'.format(bot))
    bot.loop.create_task(reminder_task())


@bot.command()
async def cancel(ctx, id):
    if ctx.channel.type == discord.ChannelType.private:
        if id.isalnum():
            con = pymysql.connect(host=host, user=user, password=password, db=db, charset=charset)
            with con:
                try:
                    cur = con.cursor()
                    cur.execute("SELECT user_id FROM reminders WHERE id = %s", id)
                    if cur.fetchall()[0][0] == ctx.author.id:
                        cur.execute("DELETE FROM reminders WHERE id = %s", id)
                        con.commit()
                        await ctx.send(f'Ваше напоминание с ID {id} было удаленно.')
                    else:
                        await ctx.send(f'Напоминание с ID {id} вам не пренадлежит. Удаление невозможно.')
                except IndexError:
                    await ctx.send(f'Напоминание с ID {id} не существует.')
        else:
            await ctx.send(f'ID может быть только цифрой.')
    else:
        await ctx.send(f'Данная команда работает только в ЛС.')


async def reminder_task():
    while True:
        await asyncio.sleep(60)
        con = pymysql.connect(host=host, user=user, password=password, db=db, charset=charset)
        with con:
            cur = con.cursor()
            cur.execute("SELECT * FROM reminders WHERE unix_time <=%s", time.time() - 60)
            for i in cur.fetchall():
                pass
                user = await bot.fetch_user(i[1])
                await user.send(f'Вам пришло напоминание \nТекст: {i[2]}')
                cur.execute("DELETE FROM reminders WHERE id = %s", i[0])
            con.commit()


bot.run(token)

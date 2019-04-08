import json

with open('graph_1.json') as json_data:
    nodes = json.load(json_data)

print('Граф с вершинами: ', [*nodes])

while True:
    start = input('Введите вершину начала пути: ')
    if start in nodes:
        break
    else:
        print('Нет такой вершины')

while True:
    finish = input('Введите вершину конца пути: ')
    if finish in nodes:
        break
    else:
        print('Нет такой вершины')


marks = {}
visited_nodes = []
unvisited_nodes = {}
negative = False

for node, neighbors in nodes.items():
    for neighbor, value in neighbors.items():
        if value < 0:
            negative = True
    if node == start:
        mark = 0
    else:
        mark = 999999
    marks.update({node: mark})
    unvisited_nodes.update({node: mark})

if negative:
    print('К сожалению, алгоритм Дейкстры не работает с рёбрами отрицательного веса.')
    exit()

while len(visited_nodes) < len(nodes):
    current_node = min(unvisited_nodes, key=unvisited_nodes.get)
    for next_node, distance in nodes[current_node].items():
        if next_node in visited_nodes:
            continue
        new_mark = marks[current_node] + distance
        if new_mark < marks[next_node]:
            marks[next_node] = new_mark
            unvisited_nodes[next_node] = new_mark
    visited_nodes.append(current_node)
    del unvisited_nodes[current_node]


current_node = finish
short_path = []
short_path.append(finish)
while current_node != start:
    for next_node, distance in nodes[current_node].items():
        if marks[current_node] - distance == marks[next_node]:
            short_path.append(next_node)
            current_node = next_node
            break

short_path.reverse()
print('Кратчайший путь из вершины ', start, ' в вершину ', finish, ': ',short_path)
print('Общий вес маршрута: ', marks[finish])

import pandas
import json
import sys
import time as TimeLib
import re

class Scheduling:

    def __init__(self):
        pass

    def findConfig(self,name):
        for element in self.configs:
            if(element.lower().find(name)!=-1):
                return int(re.sub('[^0-9]','',element))

    def GetQueueBeforeTo(self,time,queue):

        for p in queue.copy():
            if(self.data[p].values[1]>time):
                queue.pop(queue.index(p))

        return queue

    def OrderByPriorityP(self):
        SortedByPriority = []
        dicQueue = {}
        start = int(self.data[self.OrderByArrival()[0]].values[1])
        end = int(self.data[self.OrderByArrival()[-1]].values[1])+int(sum(list(self.data.values[0])))

        for time in range(start,end):
            queueArrival = self.OrderByArrivalBeforeTo(time)
            queuePriority = self.OrderByPriority(queueArrival)
            if(bool(queuePriority)):
                dicQueue.update({time:self.GetQueueBeforeTo(time,queuePriority[1:])})
                first = queuePriority[0]
                self.data[first].values[0]-=1
                SortedByPriority.append(first)

        return self.AtomicProcess(SortedByPriority,dicQueue)

    def OrderByPriorityNP(self):
        SortedByPriority = []
        dicQueue = {}
        start = int(self.data[self.OrderByArrival()[0]].values[1])
        end = int(self.data[self.OrderByArrival()[-1]].values[1])+int(sum(list(self.data.values[0])))
        time = start
        while time < end:
            queueArrival = self.OrderByArrivalBeforeTo(time)
            queuePriority = self.OrderByPriority(queueArrival)
            if(bool(queuePriority)):
                dicQueue.update({time:self.GetQueueBeforeTo(time,queuePriority[1:])})
                first = queuePriority[0]
                time+=self.data[first].values[0]
                self.data[first].values[0]=0
                SortedByPriority.append(first)
            time+=1

        return SortedByPriority

    def OrderByPriority(self,queue):#essa aqui foi monstra
        dic = {k:self.data[k].values[2] for k in queue}#Key = process name, value = process priority
        dic1 = {k:dic[k] for k in sorted(dic,reverse=True)}#order by arrival
        dic2 = {k:dic[k] for k in sorted(dic,reverse=True)}
        result = {}
        key = 0
        value = 0
        if(bool(dic1)):
            max = sorted(list(dic1.values()))[-1]
        else:
            return []

        for v in dic1.items(): #order by p_id
            v = max
            for k2,v2 in dic2.items():
                if(v2<=v):
                    v = v2
                    key = k2
                    value = v2
            try:
                dic2.pop(key)
            except:
                pass
            result.update({key:value})

        return list(result.keys())

    def OrderByArrival(self):#essa aqui foi monstra
        dic = dict(zip(self.data.keys(),self.data.values[1]))#Key = process name, value = process arrival
        dic1 = {k:dic[k] for k in sorted(dic,reverse=True)}#order by arrival
        dic2 = {k:dic[k] for k in sorted(dic,reverse=True)}
        result = {}
        key = 0
        value = 0
        max = sorted(list(dic1.values()))[-1]

        for v in dic1.items(): #order by p_id
            v = max
            for k2,v2 in dic2.items():
                if(v2<=v):
                    v = v2
                    key = k2
                    value = v2
            try:
                dic2.pop(key)
            except:
                pass
            result.update({key:value})

        return list(result.keys())

    def OrderByArrivalBeforeTo(self,chegada):#essa aqui foi monstra
        dic = dict(zip(self.data.keys(),self.data.values[1]))#Key = process name, value = process arrival
        arrival = dict(zip(self.data.keys(),self.data.values[1]))#Key = process name, value = process arrival
        dic1 = {k:dic[k] if(int(arrival[k]) <= chegada and int(self.data[k].values[0]) != 0) else 'delete' for k in sorted(dic,reverse=True)}#order by cputime
        dic2 = dic1.copy()
        for k,v in dic1.items():
            if(v=='delete'):
                dic2.pop(k)

        dic1 = dic2.copy()
        result = {}
        key = 0
        value = 0
        if(bool(dic1)):
            max = sorted(list(dic1.values()))[-1]
        else:
            return []
        for v in dic1.items(): #order by p_id
            v = max
            for k2,v2 in dic2.items():
                if(v2<=v):
                    v = v2
                    key = k2
                    value = v2
            try:
                dic2.pop(key)
            except:
                pass
            result.update({key:value})

        SortedCPUTime = list(result.keys())
        return SortedCPUTime

    def OrderByCPUTimeLaterTo(self,chegada):#essa aqui foi monstra

        dic = dict(zip(self.data.keys(),self.data.values[0]))#Key = process name, value = process cputime
        arrival = dict(zip(self.data.keys(),self.data.values[1]))#Key = process name, value = process arrival
        dic1 = {k:dic[k] if(int(arrival[k]) <= chegada and int(dic[k]) != 0) else 'delete' for k in sorted(dic,reverse=True)}#order by cputime

        dic2 = dic1.copy()
        for k,v in dic1.items():
            if(v=='delete'):
                dic2.pop(k)

        dic1 = dic2.copy()

        result = {}
        key = 0
        value = 0

        if(bool(dic1)):
            max = sorted(list(dic1.values()))[-1]
        else:
            return []

        for v in dic1.items(): #order by p_id
            v = max
            for k2,v2 in dic2.items():
                if(v2<=v):
                    v = v2
                    key = k2
                    value = v2
            try:
                dic2.pop(key)
            except:
                pass
            result.update({key:value})

        SortedCPUTime = list(result.keys())

        return SortedCPUTime

    def AtomicProcess(self,SortedProcesses,dicQueue):
        count = 1
        aux = []
        hashData = {}

        for i in range(0,len(SortedProcesses)):
            name = SortedProcesses[i]
            try:
                NextName = SortedProcesses[i+1]
            except:
                NextName = "null";

            if(name==NextName):
                count+=1
            else:
                TimeLib.sleep(0.0001)
                hash = TimeLib.time()
                hashName = f"{SortedProcesses[i]}$-${hash}"
                aux.append(hashName)
                try:
                    hashData.update({hashName:[count,int(self.data[SortedProcesses[i]][1]), dicQueue[i]]})
                except:
                    hashData.update({hashName:[count,int(self.data[SortedProcesses[i]][1]), []]})

                count=1

        SortedProcesses = aux
        self.data = hashData.copy()
        return SortedProcesses

    def OrderByCPUTimeAtomic(self):#essa aqui foi monstra
        SortedCPUTime = []
        dicQueue = {}
        start = int(self.data[self.OrderByArrival()[0]].values[1])
        end = int(self.data[self.OrderByArrival()[-1]].values[1])+int(sum(list(self.data.values[0])))

        for time in range(start,end):
            queue = self.OrderByCPUTimeLaterTo(time)
            if(bool(queue)):
                #print(f"time:{time}--{queue[0]}--{queue[1:]}")
                dicQueue.update({time-start:queue[1:]})
                first = queue[0]
                self.data[first][0] -= 1
                SortedCPUTime.append(first)

        return self.AtomicProcess(SortedCPUTime,dicQueue)

    def OrderByCPUTimeNP(self):#essa aqui foi monstra
        SortedCPUTime = []
        dicQueue = {}
        start = int(self.data[self.OrderByArrival()[0]].values[1])
        end = int(self.data[self.OrderByArrival()[-1]].values[1])+int(sum(list(self.data.values[0])))

        time = start
        count = 1
        while time < end:

            queue = self.OrderByCPUTimeLaterTo(time)
            if(bool(queue)):
                dicQueue.update({time-start-count:queue})
                first = queue[0]

                for i in range(0,int(self.data[first][0])):
                    SortedCPUTime.append(first)
                    time += 1
                self.data[first][0] = 0

            count+=1
            time+=1

        dicQueue.update({0:dicQueue[-1]})
        return self.AtomicProcess(SortedCPUTime,dicQueue)

    def OrderByQuantum(self,quantum):
        start = int(self.data[self.OrderByArrival()[0]].values[1])
        end = int(self.data[self.OrderByArrival()[-1]].values[1])+int(sum(list(self.data.values[0])))
        processes = sorted(self.OrderByArrival());
        first = sorted(self.OrderByArrivalBeforeTo(start))[0]
        queueDelete = []
        dic = dict(zip(processes,[0]*len(processes)))
        if(first==processes[0]):
            x = 0
        else:
            x = 1

        SortedByQuantum = []
        dicQueue = {}
        queue = []
        newDelete = False
        time = start
        while time<end:
            if(newDelete):
                first = queue[0]
                # print(f"DELETE {first}")
                newDelete = False

            SortedByQuantum.append(first)
            dic[first]+=1;
            if(dic[first]==self.data[first].values[0]):
                queueDelete.append(first)
                newDelete = True
                x = 0

            queue = sorted(self.OrderByArrivalBeforeTo(time))
            for element in queueDelete:
                queue.pop(queue.index(element))

            if(not bool(queue)):
                c = 0
                while not bool(queue) and c < 400:
                    time+=1
                    queue = sorted(self.OrderByArrivalBeforeTo(time))
                    for element in queueDelete:
                        queue.pop(queue.index(element))

                    c+=1

            # print(f"[{first}]  {time}--{queue}[{x}]--{queueDelete}-{dic}")
            dicQueue.update({time:self.GetQueueBeforeTo(time,queue.copy())})
            if((dic[first]%quantum)==0):
                y = x
                if(first==queue[0]):
                    x = 1
                else:
                    x = 0

                if(x==y):
                    x = 0
                try:
                    first = queue[x]
                except:
                    try:
                        first = queue[0]
                        x = 0
                    except:
                        pass
                # print(f"QUANTUM {first}-{x}")

            time+=1
        return self.AtomicProcess(SortedByQuantum,dicQueue)

    def Algorithm(self,diferentQueue=False,hashed=False,backup=False):

        if(backup):
            self.data=self.backup.copy()
        processList = dict(zip(self.sortedKeys,['']*len(self.sortedKeys)))

        initTime = int(self.data[self.sortedKeys[0]][1])
        endTime = int(self.data[self.sortedKeys[0]][1])
        count = 0
        process = {'NAME':'','TDE':'','TDR':'','TDC':'','TR':'','QUEUE':'',"START":'','END':''}
        for k in self.sortedKeys:

            process['START'] = endTime
            if(int(self.data[k][1])>endTime):
                process['START'] = int(self.data[k][1])
                endTime =  int(self.data[k][1])
            cputime = int(self.data[k][0])

            arrival = int(self.data[k][1])
            endTime += cputime
            process['END'] = endTime
            if(hashed):
                process['NAME'] = k.split('$-$')[0]
            else:
                process['NAME'] = k
            process['TDE'] = initTime-arrival if(count>0) else 0
            process['TDE'] = 0 if(process['TDE']<0) else process['TDE']
            process['TDC'] = int(self.data[k][1])
            process['TDR'] = endTime-arrival
            process['TR'] = "NULL"

            if(diferentQueue):
                process['QUEUE'] = self.data[k][2]
            else:
                process['QUEUE'] = self.GetQueueBeforeTo(endTime,self.sortedKeys[self.sortedKeys.index(k)+1:])

            initTime += cputime
            processList[k] = process.copy()
            count+=1

        if(hashed):
            report = self.ReportHashMake(processList.copy())
            processList.update({'report':report})

        return json.dumps(processList)

    def ReportHashMake(self,processList):
        report = {v['NAME']:{"NAME":v['NAME'],'TDE':0,'TDR':0,'TDC':'','TR':''} for k,v in processList.items()}
        for k,v in processList.items():
            process = v["NAME"]
            report[process]['TDR'] = v['TDR']
            report[process]['TDE'] -= (v['END']-v["START"])
            report[process]['TDC'] = v['TDC']
            report[process]['TR'] = v['TR']

        for k,v in report.items():
            report[k]['TDE'] += report[k]['TDR']


        return report

class FCFS(Scheduling):

    def __init__(self,data):
        self.data = data
        self.sortedKeys = self.OrderByArrival()
        self.json       = self.Algorithm()

class SJF_NP(Scheduling):

    def __init__(self,data):
        self.data = data
        self.first = self.OrderByArrival()[0]
        self.sortedKeys = self.OrderByCPUTimeNP()
        self.json       = self.Algorithm(diferentQueue=True,hashed=True)

class SJF_P(Scheduling):

    def __init__(self,data):
        self.data = data
        self.sortedKeys = self.OrderByCPUTimeAtomic()
        self.json       = self.Algorithm(diferentQueue=True,hashed=True)

class ROUND_ROBIN(Scheduling):

    def __init__(self,data,configs):
        self.configs = configs
        self.data = data
        self.sortedKeys = self.OrderByQuantum(self.findConfig("quantum"))
        self.json       = self.Algorithm(diferentQueue=True,hashed=True)

class Priority_NP(Scheduling):

    def __init__(self,data):
        self.data = data.copy()
        self.backup = data.copy()
        self.sortedKeys = self.OrderByPriorityNP()
        self.json       = self.Algorithm(backup=True)

class Priority_P(Scheduling):
    def __init__(self,data):
        self.data = data.copy()
        self.backup = data.copy()
        self.sortedKeys = self.OrderByPriorityP()
        self.json       = self.Algorithm(diferentQueue=True,hashed=True)

class ShedulingSelector:

    def __init__(self):
        validator = Validator()
        passOrMessage = validator.validator(sys.argv[2])
        if(passOrMessage==True):
            self.data = validator.data.copy()
            self.configs = validator.configs.copy()
            self.result = ''
            self.SelectScheduling(sys.argv[2])
        else:
            self.result = passOrMessage

    def SelectScheduling(self,scheduling):

        if(scheduling == "FCFS"):
            selectedSheduling = FCFS(self.data)
            self.result = selectedSheduling.json

        elif(scheduling == "SJF_NP"):
            selectedSheduling = SJF_NP(self.data)
            self.result = selectedSheduling.json

        elif(scheduling == "SJF_P"):
            selectedSheduling = SJF_P(self.data)
            self.result = selectedSheduling.json

        elif(scheduling == "RR"):
            selectedSheduling = ROUND_ROBIN(self.data,self.configs)
            self.result = selectedSheduling.json

        elif(scheduling == "Priority_NP"):
            selectedSheduling = Priority_NP(self.data)
            self.result = selectedSheduling.json

        elif(scheduling == "Priority_P"):
            selectedSheduling = Priority_P(self.data)
            self.result = selectedSheduling.json

class Validator():
    def __init__(self):
        self.data = pandas.DataFrame(pandas.read_excel(sys.argv[1])).drop(columns=["PROCESS"])
        self.configs = pandas.DataFrame(pandas.read_excel(sys.argv[1]))["PROCESS"]
        self.msg = ''

    def validateEmpty(self):
        check = [True,True,True]

        for p in self.data:
            if(not (self.data[p][0] > -1)):
                check[0] = False

            if(not (self.data[p][1] > -1)):
                check[1] = False

            if(not (self.data[p][2] > -1)):
                check[2] = False

        return check

    def UnnamedColumnsDelete(self):
        msg = ''
        for p in self.data.keys():
            if(p.find("Unnamed")==0):
                self.data = self.data.drop([p], axis=1)
                msg = "Processos sem nome serão desconsiderados!"

        return msg

    def validator(self,scheduling):

        self.msg = self.UnnamedColumnsDelete()
        lines = self.validateEmpty()

        if(pandas.isnull(self.configs[0])):
            return "CPU TIME precisa ser definida! <br>Verifique a formatação baixando as planilhas de testes no menu lateral."
        elif(self.configs[0].lower()!="cpu time"):
            return "CPU TIME precisa ser definida! <br>Verifique a formatação baixando as planilhas de testes no menu lateral."

        if(pandas.isnull(self.configs[1])):
            return "ARRIVAL TIME precisa ser definida! <br>Verifique a formatação baixando as planilhas de testes no menu lateral."
        elif(self.configs[1].lower()!="arrival time"):
            return "ARRIVAL TIME precisa ser definida! <br>Verifique a formatação baixando as planilhas de testes no menu lateral."

        if(lines[0] != True):
            return "Em CPU TIME a uma celula vazia!"

        if(lines[1] != True):
            return "Em ARRIVAL TIME a uma celula vazia!"

        if(scheduling == "RR"):
            if(pandas.isnull(self.configs[2])):
                return "Para o escalonador RoundRobin, um QUANTUM precisa ser definido abaixo de Arrival Time. <br>Verifique a formatação baixando as planilhas de testes no menu lateral."
            elif(self.configs[2].lower().find('quantum')!=0):
                return "Para o escalonador RoundRobin, um QUANTUM precisa ser definido abaixo de Arrival Time. <br>Verifique a formatação baixando as planilhas de testes no menu lateral."

        if(scheduling == "Priority_P" or scheduling == "Priority_NP"):
            if(pandas.isnull(self.configs[2])):
                return "PRIORITY precisa ser definida para Escalonadores de prioridade! <br>Verifique a formatação baixando as planilhas de testes no menu lateral."
            elif(self.configs[2].lower()!="priority"):
                return "PRIORITY precisa ser definida para Escalonadores de prioridade! <br>Verifique a formatação baixando as planilhas de testes no menu lateral."

            if(lines[2] != True):
                return "Em PRIORITY a uma celula vazia!"

        return True

try:
    run = ShedulingSelector()
except:
    run.result = """Ocorreu uma exceção, não documentada. Verifique a formatação do seu arquivo de simulação.
    <br>No menu lateral é possivel fazer o download de arquivos de teste funcionais!
    <br>Caso nada funcione, é possivel que o servidor de execução desta aplicação não possua Python devidamente instalado!
    """
print(run.result)
